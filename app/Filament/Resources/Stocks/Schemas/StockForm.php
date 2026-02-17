<?php

declare(strict_types=1);

namespace App\Filament\Resources\Stocks\Schemas;

use App\Enums\StockStatus;
use App\Models\WarehouseLocation;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

final class StockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                Select::make('warehouse_id')
                    ->relationship('warehouse', 'name')
                    ->required()
                    ->live(),
                Select::make('warehouse_location_id')
                    ->label(__('Warehouse Locations'))
                    ->options(fn (Get $get) => WarehouseLocation::query()
                        ->where('warehouse_id', $get('warehouse_id'))
                        ->where('is_active', true)
                        ->pluck('code', 'id'))
                    ->searchable()
                    ->visible(fn (Get $get): bool => filled($get('warehouse_id'))),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('reserved_quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('minimum_stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('maximum_stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('batch_id')
                    ->relationship('batch', 'id'),
                Select::make('status')
                    ->options(StockStatus::class)
                    ->default(StockStatus::IN_STOCK)
                    ->required(),
                TextInput::make('unit_cost')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total_value')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
