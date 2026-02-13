<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\NavigationGroup;
use App\Models\Product;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use UnitEnum;

final class AbcAnalysisReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.abc-analysis-report';

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::REPORTS;

    protected static ?string $title = 'ABC Analysis';

    protected static ?string $navigationLabel = 'ABC Analysis';

    protected static ?int $navigationSort = 20;

    /**
     * @var Collection<int, array{id: int, value: float, cumulative_percentage: float, category: string}>|null
     */
    private ?Collection $abcAnalysisCache = null;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->with(['stocks'])
                    ->whereHas('stocks')
            )
            ->columns([
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Product')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                TextColumn::make('category.name')
                    ->label('Categories')
                    ->searchable(),

                TextColumn::make('total_stock_value')
                    ->label('Total Stock Value')
                    ->state(fn (Product $record): float => $this->calculateStockValue($record))
                    ->money('HUF')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderByRaw(
                            '(SELECT COALESCE(SUM(stocks.quantity * stocks.unit_cost), 0) FROM stocks WHERE stocks.product_id = products.id AND stocks.deleted_at IS NULL) '.$direction
                        );
                    })
                    ->alignEnd(),

                TextColumn::make('abc_category')
                    ->label('ABC Category')
                    ->state(fn (Product $record): string => $this->getAbcAnalysis($record->id)['category'])
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'A' => 'success',
                        'B' => 'warning',
                        default => 'gray',
                    })
                    ->alignCenter(),

                TextColumn::make('cumulative_percentage')
                    ->label('Cumulative %')
                    ->state(fn (Product $record): string => number_format($this->getAbcAnalysis($record->id)['cumulative_percentage'], 1).'%')
                    ->alignEnd(),
            ])
            ->defaultSort('total_stock_value', 'desc')
            ->paginated([10, 25, 50, 100])
            ->heading(__('Product categorization by value'))
            ->emptyStateHeading(__('No product data available'))
            ->emptyStateIcon(Heroicon::OutlinedChartBar);
    }

    private function calculateStockValue(Product $product): float
    {
        return $product->stocks->sum(fn ($stock): float => $stock->quantity * ($stock->unit_cost ?? 0));
    }

    /**
     * @return array{id: int, value: float, cumulative_percentage: float, category: string}
     */
    private function getAbcAnalysis(int $productId): array
    {
        $analysis = $this->getAbcAnalysisData();

        return $analysis->firstWhere('id', $productId) ?? [
            'id' => $productId,
            'value' => 0.0,
            'cumulative_percentage' => 0.0,
            'category' => 'C',
        ];
    }

    /**
     * @return Collection<int, array{id: int, value: float, cumulative_percentage: float, category: string}>
     */
    private function getAbcAnalysisData(): Collection
    {
        if ($this->abcAnalysisCache !== null) {
            return $this->abcAnalysisCache;
        }

        $products = Product::query()
            ->with('stocks')
            ->whereHas('stocks')
            ->get()
            ->map(fn (Product $p): array => [
                'id' => $p->id,
                'value' => $this->calculateStockValue($p),
            ])
            ->sortByDesc('value')
            ->values();

        $totalValue = $products->sum('value');

        $cumulative = 0.0;
        $this->abcAnalysisCache = $products->map(function (array $item) use ($totalValue, &$cumulative): array {
            $cumulative += $item['value'];
            $percentage = $totalValue > 0 ? ($cumulative / $totalValue) * 100 : 0.0;

            return [
                'id' => $item['id'],
                'value' => $item['value'],
                'cumulative_percentage' => $percentage,
                'category' => match (true) {
                    $percentage <= 80 => 'A',
                    $percentage <= 95 => 'B',
                    default => 'C',
                },
            ];
        });

        return $this->abcAnalysisCache;
    }
}
