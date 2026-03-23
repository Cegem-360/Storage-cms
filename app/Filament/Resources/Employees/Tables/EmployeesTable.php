<?php

declare(strict_types=1);

namespace App\Filament\Resources\Employees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

final class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee_code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('first_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('last_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('position')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('department')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('warehouse.name')
                    ->label(__('Warehouse'))
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('phone')
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('user.name')
                    ->label(__('Linked user'))
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean(),
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
                TernaryFilter::make('is_active')
                    ->label(__('Active'))
                    ->trueLabel(__('Active only'))
                    ->falseLabel(__('Inactive only')),
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
            ])
            ->defaultSort('last_name', 'asc');
    }

    public static function configureDashboard(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee_code')
                    ->label(__('Code'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('full_name')
                    ->label(__('Name'))
                    ->state(fn ($record): string => $record->last_name.' '.$record->first_name)
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(['last_name', 'first_name']),
                TextColumn::make('position')
                    ->label(__('Position'))
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('department')
                    ->label(__('Department'))
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('warehouse.name')
                    ->label(__('Warehouse'))
                    ->placeholder('-'),
                IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label(__('Active'))
                    ->trueLabel(__('Active only'))
                    ->falseLabel(__('Inactive only')),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->defaultSort('last_name', 'asc')
            ->paginated([10, 25, 50, 100]);
    }
}
