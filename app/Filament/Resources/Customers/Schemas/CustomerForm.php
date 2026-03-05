<?php

declare(strict_types=1);

namespace App\Filament\Resources\Customers\Schemas;

use App\Enums\CustomerType;
use App\Models\Team;
use App\Services\PostalCodeLookupService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

final class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->tabs([
                        Tab::make('customer_information')
                            ->label(__('Customer Information'))
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

                        Tab::make('addresses')
                            ->label(__('Addresses'))
                            ->schema([
                                Section::make(__('Billing address'))
                                    ->schema([
                                        TextInput::make('billing_address.street')
                                            ->label(__('Street')),
                                        TextInput::make('billing_address.city')
                                            ->label(__('City')),
                                        TextInput::make('billing_address.state')
                                            ->label(__('State')),
                                        TextInput::make('billing_address.postal_code')
                                            ->label(__('Postal Code'))
                                            ->live(debounce: 500)
                                            ->afterStateUpdated(function (?string $state, Set $set): void {
                                                if ($state && ($city = PostalCodeLookupService::lookup($state))) {
                                                    $set('billing_address.city', $city);
                                                    $set('billing_address.country', 'Magyarország');
                                                }
                                            }),
                                        TextInput::make('billing_address.country')
                                            ->label(__('Country')),
                                    ])
                                    ->columns(2),

                                Toggle::make('same_as_billing')
                                    ->label(__('Shipping address same as billing'))
                                    ->default(function (?Model $record): bool {
                                        if (! $record) {
                                            return false;
                                        }

                                        $billing = $record->billing_address ?? [];
                                        $shipping = $record->shipping_address ?? [];

                                        return ! empty($billing) && $billing === $shipping;
                                    })
                                    ->live()
                                    ->afterStateUpdated(function (bool $state, Get $get, Set $set): void {
                                        if ($state) {
                                            $set('shipping_address.street', $get('billing_address.street'));
                                            $set('shipping_address.city', $get('billing_address.city'));
                                            $set('shipping_address.state', $get('billing_address.state'));
                                            $set('shipping_address.postal_code', $get('billing_address.postal_code'));
                                            $set('shipping_address.country', $get('billing_address.country'));
                                        }
                                    })
                                    ->dehydrated(false)
                                    ->columnSpanFull(),

                                Section::make(__('Shipping address'))
                                    ->schema([
                                        TextInput::make('shipping_address.street')
                                            ->label(__('Street')),
                                        TextInput::make('shipping_address.city')
                                            ->label(__('City')),
                                        TextInput::make('shipping_address.state')
                                            ->label(__('State')),
                                        TextInput::make('shipping_address.postal_code')
                                            ->label(__('Postal Code'))
                                            ->live(debounce: 500)
                                            ->afterStateUpdated(function (?string $state, Set $set): void {
                                                if ($state && ($city = PostalCodeLookupService::lookup($state))) {
                                                    $set('shipping_address.city', $city);
                                                    $set('shipping_address.country', 'Magyarország');
                                                }
                                            }),
                                        TextInput::make('shipping_address.country')
                                            ->label(__('Country')),
                                    ])
                                    ->columns(2)
                                    ->visible(fn (Get $get): bool => ! $get('same_as_billing')),
                            ]),

                        Tab::make('financial_information')
                            ->label(__('Financial Information'))
                            ->schema([
                                TextInput::make('credit_limit')
                                    ->numeric()
                                    ->default(0.0)
                                    ->prefix(Team::currency())
                                    ->helperText(__('Set to 0 for unlimited credit')),
                                TextInput::make('balance')
                                    ->numeric()
                                    ->default(0.0)
                                    ->prefix(Team::currency())
                                    ->disabled()
                                    ->helperText(__('Automatically calculated from outstanding invoices')),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
