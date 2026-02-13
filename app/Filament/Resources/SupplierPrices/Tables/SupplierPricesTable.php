<?php

declare(strict_types=1);

namespace App\Filament\Resources\SupplierPrices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

final class SupplierPricesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('supplier.company_name')
                    ->label('Supplier')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                    ->label('Price')
                    ->money('HUF')
                    ->sortable(),
                TextColumn::make('currency')
                    ->label('Currency')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('minimum_order_quantity')
                    ->label('Min. Qty')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('lead_time_days')
                    ->label('Lead Time')
                    ->suffix(' '.__('days'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('valid_from')
                    ->label('Valid From')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('valid_until')
                    ->label('Valid Until')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active'),
                SelectFilter::make('supplier_id')
                    ->label('Supplier')
                    ->relationship('supplier', 'company_name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('product_id')
                    ->label('Product')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
