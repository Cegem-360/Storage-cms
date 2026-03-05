<?php

declare(strict_types=1);

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

final class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->tabs([
                        Tab::make('company')
                            ->schema([
                                TextInput::make('code'),
                                TextInput::make('company_name')
                                    ->required(),
                                TextInput::make('trade_name'),
                                TextInput::make('tax_number'),
                                TextInput::make('eu_tax_number'),
                                TextInput::make('company_registration_number'),
                                TextInput::make('bank_account_number'),
                                TextInput::make('rating'),
                                Toggle::make('is_active')
                                    ->label(__('Active'))
                                    ->required(),
                            ])
                            ->columns(2),

                        Tab::make('addresses')
                            ->schema([
                                Section::make('headquarters_address')
                                    ->schema([
                                        TextInput::make('headquarters.street')
                                            ->label(__('Street')),
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('headquarters.city')
                                                    ->label(__('City')),
                                                TextInput::make('headquarters.state')
                                                    ->label(__('State')),
                                            ]),
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('headquarters.zip')
                                                    ->label(__('Zip Code')),
                                                TextInput::make('headquarters.country')
                                                    ->label(__('Country')),
                                            ]),
                                    ]),

                                Section::make('mailing_address')
                                    ->schema([
                                        TextInput::make('mailing_address.street')
                                            ->label(__('Street')),
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('mailing_address.city')
                                                    ->label(__('City')),
                                                TextInput::make('mailing_address.state')
                                                    ->label(__('State')),
                                            ]),
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('mailing_address.zip')
                                                    ->label(__('Zip Code')),
                                                TextInput::make('mailing_address.country')
                                                    ->label(__('Country')),
                                            ]),
                                    ]),
                            ]),

                        Tab::make('contact')
                            ->schema([
                                TextInput::make('contact_person'),
                                TextInput::make('email')
                                    ->label(__('Email address'))
                                    ->email(),
                                TextInput::make('phone')
                                    ->tel(),
                                TextInput::make('website')
                                    ->url(),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
