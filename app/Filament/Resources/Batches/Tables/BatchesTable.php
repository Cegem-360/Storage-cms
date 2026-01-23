<?php

declare(strict_types=1);

namespace App\Filament\Resources\Batches\Tables;

use App\Models\Batch;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class BatchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('batch_number')
                    ->label('Batch Number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('supplier.company_name')
                    ->label('Supplier')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('manufacture_date')
                    ->label('Manufacture Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('expiry_date')
                    ->label('Expiry Date')
                    ->date()
                    ->sortable()
                    ->color(fn ($record) => $record->isExpired() ? 'danger' : null),
                TextColumn::make('quality_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'APPROVED' => 'success',
                        'PENDING_CHECK' => 'warning',
                        'REJECTED' => 'danger',
                        'QUARANTINE' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function configureDashboard(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('batch_number')
                    ->label(__('Batch #'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label(__('Product'))
                    ->searchable(),
                TextColumn::make('warehouse.name')
                    ->label(__('Warehouse'))
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label(__('Quantity'))
                    ->numeric(),
                TextColumn::make('expiry_date')
                    ->label(__('Expiry Date'))
                    ->date()
                    ->sortable()
                    ->color(fn ($record) => $record->isExpired() ? 'danger' : null),
            ])
            ->recordActions([
                Action::make('edit')
                    ->url(fn (Batch $record): string => route('filament.admin.resources.batches.edit', $record))
                    ->icon(Heroicon::PencilSquare)
                    ->color('gray'),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50, 100]);
    }
}
