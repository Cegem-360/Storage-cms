<?php

declare(strict_types=1);

namespace App\Filament\Resources\IntrastatOutbounds\Tables;

use App\Enums\IntrastatStatus;
use App\Models\IntrastatDeclaration;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

use function mb_str_pad;

final class IntrastatOutboundsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('declaration_number')
                    ->label('Nyilatkozat szám')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('reference_year')
                    ->label('Év')
                    ->sortable(),
                TextColumn::make('reference_month')
                    ->label('Hónap')
                    ->sortable(),
                TextColumn::make('declaration_date')
                    ->label('Nyilatkozat dátuma')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Státusz')
                    ->badge()
                    ->color(fn (IntrastatStatus $state): string => match ($state) {
                        IntrastatStatus::DRAFT => 'gray',
                        IntrastatStatus::READY => 'success',
                        IntrastatStatus::SUBMITTED => 'info',
                        default => 'warning',
                    }),
                TextColumn::make('total_invoice_value')
                    ->label('Összérték')
                    ->money('HUF')
                    ->sortable(),
                TextColumn::make('intrastatLines_count')
                    ->label('Tételek száma')
                    ->counts('intrastatLines'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('declaration_date', 'desc');
    }

    public static function configureDashboard(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('declaration_number')
                    ->label(__('Declaration #'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('reference_period')
                    ->label(__('Period'))
                    ->getStateUsing(fn ($record) => $record->reference_year.'/'.mb_str_pad((string) $record->reference_month, 2, '0', STR_PAD_LEFT))
                    ->sortable(),
                TextColumn::make('intrastat_lines_count')
                    ->label(__('Lines'))
                    ->counts('intrastatLines')
                    ->badge()
                    ->color('success'),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('total_invoice_value')
                    ->label(__('Total Value'))
                    ->money('EUR')
                    ->sortable(),
                TextColumn::make('total_net_mass')
                    ->label(__('Net Mass'))
                    ->numeric(decimalPlaces: 2)
                    ->suffix(' kg')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(IntrastatStatus::class),
            ])
            ->recordActions([
                Action::make('edit')
                    ->url(fn (IntrastatDeclaration $record): string => route('filament.admin.resources.intrastat-declarations.edit', $record))
                    ->icon(Heroicon::PencilSquare)
                    ->color('gray'),
            ])
            ->defaultSort('declaration_date', 'desc')
            ->paginated([10, 25, 50, 100]);
    }
}
