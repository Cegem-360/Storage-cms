<?php

declare(strict_types=1);

namespace App\Filament\Resources\SupplierPrices\Schemas;

use App\Models\Team;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
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
                Section::make('price_information')
                    ->schema([
                        Select::make('supplier_id')
                            ->label(__('Supplier'))
                            ->relationship('supplier', 'company_name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('product_id')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('price')
                            ->numeric()
                            ->required()
                            ->prefix(Team::currency()),
                        TextInput::make('currency')
                            ->default('HUF')
                            ->required()
                            ->maxLength(3),
                        TextInput::make('minimum_order_quantity')
                            ->label(__('Min. Order Quantity'))
                            ->numeric()
                            ->default(1)
                            ->minValue(1),
                        TextInput::make('lead_time_days')
                            ->label(__('Lead Time'))
                            ->numeric()
                            ->suffix(__('days')),
                    ])
                    ->columns(2),

                Section::make('validity')
                    ->schema([
                        DatePicker::make('valid_from'),
                        DatePicker::make('valid_until'),
                        Toggle::make('is_active')
                            ->label(__('Active'))
                            ->default(true),
                        Textarea::make('notes')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('quantity_discount_tiers')
                    ->schema([
                        Repeater::make('tiers')
                            ->label('')
                            ->relationship()
                            ->schema([
                                TextInput::make('min_quantity')
                                    ->label(__('Min. Quantity'))
                                    ->numeric()
                                    ->required()
                                    ->minValue(1),
                                TextInput::make('max_quantity')
                                    ->label(__('Max. Quantity'))
                                    ->numeric()
                                    ->nullable(),
                                TextInput::make('price')
                                    ->numeric()
                                    ->required()
                                    ->prefix(Team::currency()),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->reorderable(false)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => isset($state['min_quantity'], $state['price'])
                                ? "{$state['min_quantity']}+ ".__('pcs')." → {$state['price']} ".Team::currency()
                                : null
                            ),
                    ]),
            ]);
    }
}
