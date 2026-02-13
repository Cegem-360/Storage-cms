<?php

declare(strict_types=1);

namespace App\Filament\Resources\SupplierPrices\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class SupplierPriceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Price Information'))
                    ->schema([
                        Select::make('supplier_id')
                            ->label('Supplier')
                            ->relationship('supplier', 'company_name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('product_id')
                            ->label('Product')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('price')
                            ->label('Price')
                            ->numeric()
                            ->required()
                            ->prefix('HUF'),
                        TextInput::make('currency')
                            ->label('Currency')
                            ->default('HUF')
                            ->required()
                            ->maxLength(3),
                        TextInput::make('minimum_order_quantity')
                            ->label('Min. Order Quantity')
                            ->numeric()
                            ->default(1)
                            ->minValue(1),
                        TextInput::make('lead_time_days')
                            ->label('Lead Time')
                            ->numeric()
                            ->suffix(__('days')),
                    ])
                    ->columns(2),

                Section::make(__('Validity'))
                    ->schema([
                        DatePicker::make('valid_from')
                            ->label('Valid From'),
                        DatePicker::make('valid_until')
                            ->label('Valid Until'),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                        Textarea::make('notes')
                            ->label('Notes')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
