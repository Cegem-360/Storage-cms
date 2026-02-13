<?php

declare(strict_types=1);

namespace App\Filament\Resources\Stocks\Tables;

use App\Models\Stock;
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
                    ->color(fn (Stock $record): string => $record->getStockLevel()->getColor()),
                TextColumn::make('reserved_quantity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('available_quantity')
                    ->label('Available Stock')
                    ->state(fn (Stock $record): int => $record->getAvailableQuantity())
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (Stock $record): string => $record->getStockLevel()->getColor()),
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
                    ->icon(fn (Stock $record): string => $record->getStockLevel()->getIcon()->value)
                    ->color(fn (Stock $record): string => $record->getStockLevel()->getColor())
                    ->tooltip(fn (Stock $record): string => $record->getStockLevel()->getLabel()),
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
                    ->color(fn (Stock $record): string => $record->getStockLevel()->getColor()),
                TextColumn::make('minimum_stock')
                    ->label('Minimum')
                    ->numeric(),
                IconColumn::make('alert')
                    ->label('Status')
                    ->icon(fn (Stock $record): string => $record->getStockLevel()->getIcon()->value)
                    ->color(fn (Stock $record): string => $record->getStockLevel()->getColor()),
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
}
