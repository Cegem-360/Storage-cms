<?php

declare(strict_types=1);

namespace App\Filament\Resources\ReturnDeliveries\Tables;

use App\Enums\ReturnStatus;
use App\Enums\ReturnType;
use App\Models\ReturnDelivery;
use App\Models\Team;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

final class ReturnDeliveriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('return_number')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label(__('Return Type'))
                    ->badge()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('warehouse.name')
                    ->label(__('Warehouse'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('return_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('customer.name')
                    ->label(__('Customer'))
                    ->searchable()
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('supplier.name')
                    ->label(__('Supplier'))
                    ->searchable()
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('total_amount')
                    ->label(__('Total'))
                    ->money(Team::currency())
                    ->sortable(),

                TextColumn::make('reason')
                    ->badge()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options(ReturnType::class),

                SelectFilter::make('status')
                    ->options(ReturnStatus::class),

                SelectFilter::make('warehouse')
                    ->relationship('warehouse', 'name'),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('return_date', 'desc');
    }

    public static function configureDashboard(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('return_number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('order.order_number')
                    ->label(__('Order'))
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('warehouse.name')
                    ->label(__('Warehouse'))
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('return_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->label(__('Total'))
                    ->money(Team::currency())
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(ReturnStatus::class),
            ])
            ->recordActions([
                Action::make('view')
                    ->url(fn (ReturnDelivery $record): string => route('dashboard.return-deliveries.view', $record))
                    ->icon(Heroicon::Eye)
                    ->color('gray'),
                Action::make('edit')
                    ->url(fn (ReturnDelivery $record): string => route('dashboard.return-deliveries.edit', $record))
                    ->icon(Heroicon::PencilSquare)
                    ->color('gray'),
            ])
            ->defaultSort('return_date', 'desc')
            ->paginated([10, 25, 50, 100]);
    }
}
