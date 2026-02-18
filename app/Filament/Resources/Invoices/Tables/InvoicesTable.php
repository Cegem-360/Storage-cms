<?php

declare(strict_types=1);

namespace App\Filament\Resources\Invoices\Tables;

use App\Enums\InvoiceStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

final class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->label(__('Invoice Number'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('order.order_number')
                    ->label(__('Order'))
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('customer.name')
                    ->label(__('Customer'))
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('supplier.company_name')
                    ->label(__('Supplier'))
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('invoice_date')
                    ->label(__('Invoice Date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('due_date')
                    ->label(__('Due Date'))
                    ->date()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->label(__('Total'))
                    ->money('HUF')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options(InvoiceStatus::class),
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
}
