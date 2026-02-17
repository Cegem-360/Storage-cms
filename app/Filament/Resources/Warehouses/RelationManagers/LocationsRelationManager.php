<?php

declare(strict_types=1);

namespace App\Filament\Resources\Warehouses\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;

final class LocationsRelationManager extends RelationManager
{
    protected static string $relationship = 'locations';

    protected static ?string $title = 'Warehouse Locations';

    #[Override]
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label(__('Location Code'))
                    ->required()
                    ->maxLength(50),
                TextInput::make('zone')
                    ->label(__('Zone'))
                    ->maxLength(50),
                TextInput::make('row')
                    ->label(__('Row'))
                    ->maxLength(50),
                TextInput::make('shelf')
                    ->label(__('Shelf'))
                    ->maxLength(50),
                TextInput::make('level')
                    ->label(__('Level'))
                    ->maxLength(50),
                Toggle::make('is_active')
                    ->label(__('Active'))
                    ->default(true),
                Textarea::make('notes')
                    ->label(__('Notes'))
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('code')
            ->columns([
                TextColumn::make('code')
                    ->label(__('Location Code'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('zone')
                    ->label(__('Zone'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('row')
                    ->label(__('Row'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('shelf')
                    ->label(__('Shelf'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('level')
                    ->label(__('Level'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('stocks_count')
                    ->label(__('Stock Items'))
                    ->counts('stocks')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
