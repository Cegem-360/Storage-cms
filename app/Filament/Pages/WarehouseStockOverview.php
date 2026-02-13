<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\NavigationGroup;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Warehouse;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use UnitEnum;

final class WarehouseStockOverview extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.warehouse-stock-overview';

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::REPORTS;

    protected static ?string $title = 'Warehouse Stock Overview';

    protected static ?string $navigationLabel = 'Stock by Warehouse';

    protected static ?int $navigationSort = 10;

    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query()->with(['stocks.warehouse']))
            ->columns($this->getColumns())
            ->defaultSort('name');
    }

    private function getColumns(): array
    {
        $warehouses = Warehouse::query()->where('is_active', true)->get();

        $columns = [
            TextColumn::make('sku')
                ->label('SKU')
                ->sortable()
                ->searchable(),
            TextColumn::make('name')
                ->label('Product Name')
                ->sortable()
                ->searchable(),
        ];

        foreach ($warehouses as $warehouse) {
            $columns[] = TextColumn::make("stock_warehouse_{$warehouse->id}")
                ->label($warehouse->name)
                ->state(fn (Product $record): string => (string) ($this->findStock($record, $warehouse->id)?->quantity ?? 0))
                ->alignEnd()
                ->badge()
                ->color(function (Product $record) use ($warehouse): string {
                    $stock = $this->findStock($record, $warehouse->id);

                    if (! $stock instanceof Stock || $stock->quantity === 0) {
                        return 'gray';
                    }

                    return $stock->isLowStock() ? 'danger' : 'success';
                });
        }

        $columns[] = TextColumn::make('total_stock')
            ->label('Total Stock')
            ->state(fn (Product $record): int => $record->stocks->sum('quantity'))
            ->alignEnd()
            ->weight('bold')
            ->badge()
            ->color('primary');

        $columns[] = TextColumn::make('available_stock')
            ->label('Available Stock')
            ->state(fn (Product $record): int => $record->stocks->sum(fn (Stock $stock): int => $stock->getAvailableQuantity()))
            ->alignEnd()
            ->badge()
            ->color('success');

        return $columns;
    }

    private function findStock(Product $product, int $warehouseId): ?Stock
    {
        return $product->stocks->firstWhere('warehouse_id', $warehouseId);
    }
}
