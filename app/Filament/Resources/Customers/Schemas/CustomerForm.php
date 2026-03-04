<?php

declare(strict_types=1);

namespace App\Filament\Resources\Customers\Schemas;

use App\Enums\CustomerType;
use App\Models\Team;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

final class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->tabs([
                        Tab::make(__('Customer Information'))
                            ->schema([
                                TextInput::make('customer_code'),
                                TextInput::make('name')
                                    ->required(),
                                TextInput::make('email')
                                    ->label(__('Email address'))
                                    ->email()
                                    ->required(),
                                TextInput::make('phone')
                                    ->tel(),
                                Select::make('type')
                                    ->options(CustomerType::class)
                                    ->enum(CustomerType::class),
                            ])
                            ->columns(2),

                        Tab::make(__('Addresses'))
                            ->schema([
                                Section::make(__('Billing Address'))
                                    ->schema([
                                        TextInput::make('billing_address.street')
                                            ->label(__('Street')),
                                        TextInput::make('billing_address.city')
                                            ->label(__('City')),
                                        TextInput::make('billing_address.state')
                                            ->label(__('State')),
                                        TextInput::make('billing_address.postal_code')
                                            ->label(__('Postal Code')),
                                        TextInput::make('billing_address.country')
                                            ->label(__('Country')),
                                    ])
                                    ->columns(2),

                                Section::make(__('Shipping Address'))
                                    ->schema([
                                        TextInput::make('shipping_address.street')
                                            ->label(__('Street')),
                                        TextInput::make('shipping_address.city')
                                            ->label(__('City')),
                                        TextInput::make('shipping_address.state')
                                            ->label(__('State')),
                                        TextInput::make('shipping_address.postal_code')
                                            ->label(__('Postal Code')),
                                        TextInput::make('shipping_address.country')
                                            ->label(__('Country')),
                                    ])
                                    ->columns(2),
                            ]),

                        Tab::make(__('Financial Information'))
                            ->schema([
                                TextInput::make('credit_limit')
                                    ->numeric()
                                    ->default(0.0)
                                    ->prefix(Team::currency()),
                                TextInput::make('balance')
                                    ->numeric()
                                    ->default(0.0)
                                    ->prefix(Team::currency()),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
