<?php

declare(strict_types=1);

namespace App\Filament\Resources\Stocks\Tables;

use App\Models\Stock;
use Closure;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

final class StocksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->searchable(),
                TextColumn::make('warehouse.name')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(self::stockStatusColor()),
                TextColumn::make('reserved_quantity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('available_quantity')
                    ->label('Available Stock')
                    ->state(fn (Stock $record): int => $record->getAvailableQuantity())
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (Stock $record): string => match (true) {
                        $record->getAvailableQuantity() === 0 => 'danger',
                        $record->getAvailableQuantity() <= $record->minimum_stock => 'warning',
                        default => 'success',
                    }),
                TextColumn::make('minimum_stock')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('maximum_stock')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('batch.id')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),

                IconColumn::make('alert')
                    ->label('Alert')
                    ->icon(self::stockStatusIcon())
                    ->color(self::stockStatusColor())
                    ->tooltip(fn (Stock $record): string => match ($record->getStockStatus()) {
                        'out_of_stock' => 'Out of stock',
                        'low_stock' => "Low stock: {$record->quantity} (min: {$record->minimum_stock})",
                        'overstock' => "Overstock: {$record->quantity} (max: {$record->maximum_stock})",
                        default => 'Stock level OK',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function configureDashboard(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('warehouse.name')
                    ->label('Warehouse')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(self::stockStatusColor()),
                TextColumn::make('minimum_stock')
                    ->label('Minimum')
                    ->numeric(),
                IconColumn::make('alert')
                    ->label('Status')
                    ->icon(self::stockStatusIcon())
                    ->color(self::stockStatusColor()),
            ])
            ->filters([
                TernaryFilter::make('low_stock')
                    ->label('Low stock only')
                    ->queries(
                        true: fn ($query) => $query->whereColumn('quantity', '<', 'minimum_stock'),
                        false: fn ($query) => $query->whereColumn('quantity', '>=', 'minimum_stock'),
                    ),
            ])
            ->recordActions([
                Action::make('edit')
                    ->url(fn (Stock $record): string => route('filament.admin.resources.stocks.edit', $record))
                    ->icon(Heroicon::PencilSquare)
                    ->color('gray'),
            ])
            ->defaultSort('quantity', 'desc')
            ->paginated([10, 25, 50, 100]);
    }

    private static function stockStatusColor(): Closure
    {
        return fn (Stock $record): string => match ($record->getStockStatus()) {
            'out_of_stock' => 'danger',
            'low_stock' => 'warning',
            'overstock' => 'info',
            default => 'success',
        };
    }

    private static function stockStatusIcon(): Closure
    {
        return fn (Stock $record): string => match ($record->getStockStatus()) {
            'out_of_stock' => Heroicon::OutlinedXCircle,
            'low_stock' => Heroicon::OutlinedExclamationTriangle,
            'overstock' => Heroicon::OutlinedArrowTrendingUp,
            default => Heroicon::OutlinedCheckCircle,
        };
    }
}
