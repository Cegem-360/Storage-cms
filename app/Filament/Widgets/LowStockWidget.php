<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Stock;
use App\Models\Team;
use App\Models\User;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Override;

final class LowStockWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = null;

    public function getHeading(): ?string
    {
        return __('Low Stock Alerts');
    }

    #[Override]
    public function table(Table $table): Table
    {
        $teamThreshold = $this->getTeamThreshold();

        return $table
            ->query(
                Stock::query()
                    ->with(['product', 'warehouse'])
                    ->where(function ($query) use ($teamThreshold): void {
                        $query->where(function ($q): void {
                            $q->where('minimum_stock', '>', 0)
                                ->whereColumn('quantity', '<=', 'minimum_stock');
                        });

                        if ($teamThreshold > 0) {
                            $query->orWhere(function ($q) use ($teamThreshold): void {
                                $q->where('minimum_stock', 0)
                                    ->where('quantity', '<=', $teamThreshold);
                            });
                        }
                    })
                    ->orderBy('quantity')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('product.sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('warehouse.name')
                    ->label('Warehouse')
                    ->sortable(),

                TextColumn::make('quantity')
                    ->label('Current')
                    ->numeric()
                    ->sortable()
                    ->color('danger'),

                TextColumn::make('minimum_stock')
                    ->label('Minimum')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('available_quantity')
                    ->label('Available Stock')
                    ->state(fn (Stock $record): int => $record->getAvailableQuantity())
                    ->numeric()
                    ->color('warning'),

                TextColumn::make('difference')
                    ->label('Shortage')
                    ->state(function (Stock $record) use ($teamThreshold): int {
                        $minimum = $record->minimum_stock > 0
                            ? $record->minimum_stock
                            : $teamThreshold;

                        return $minimum - $record->quantity;
                    })
                    ->numeric()
                    ->color('danger')
                    ->prefix('-'),
            ])
            ->paginated(false)
            ->emptyStateHeading(__('No low stock alerts'))
            ->emptyStateDescription(__('All products are above minimum stock levels.'))
            ->emptyStateIcon(Heroicon::OutlinedCheckCircle);
    }

    private function getTeamThreshold(): int
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (! $user?->team_id) {
            return 0;
        }

        $team = Team::query()->with('settings')->find($user->team_id);

        return (int) $team?->getSetting('low_stock_threshold', 0);
    }
}
