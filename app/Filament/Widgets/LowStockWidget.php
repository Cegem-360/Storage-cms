<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Stock;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

final class LowStockWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = null;

    public function getHeading(): ?string
    {
        return __('Low Stock Alerts');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Stock::query()
                    ->with(['product', 'warehouse'])
                    ->whereColumn('quantity', '<=', 'minimum_stock')
                    ->orderBy('quantity')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('product.sku')
                    ->label(__('SKU'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('product.name')
                    ->label(__('Product'))
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('warehouse.name')
                    ->label(__('Warehouse'))
                    ->sortable(),

                TextColumn::make('quantity')
                    ->label(__('Current'))
                    ->numeric()
                    ->sortable()
                    ->color('danger'),

                TextColumn::make('minimum_stock')
                    ->label(__('Minimum'))
                    ->numeric()
                    ->sortable(),

                TextColumn::make('difference')
                    ->label(__('Shortage'))
                    ->state(fn (Stock $record): int => $record->minimum_stock - $record->quantity)
                    ->numeric()
                    ->color('danger')
                    ->prefix('-'),
            ])
            ->paginated(false)
            ->emptyStateHeading(__('No low stock alerts'))
            ->emptyStateDescription(__('All products are above minimum stock levels.'))
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}
