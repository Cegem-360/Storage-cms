<?php

declare(strict_types=1);

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

final class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->tabs([
                        Tab::make('personal_information')
                            ->schema([
                                TextInput::make('employee_code')
                                    ->required()
                                    ->maxLength(50)
                                    ->scopedUnique(ignoreRecord: true),
                                TextInput::make('first_name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('last_name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(50),
                            ])
                            ->columns(2),

                        Tab::make('work_information')
                            ->schema([
                                TextInput::make('position')
                                    ->maxLength(255),
                                TextInput::make('department')
                                    ->maxLength(100),
                                Select::make('warehouse_id')
                                    ->relationship('warehouse', 'name')
                                    ->label(__('Warehouse'))
                                    ->searchable()
                                    ->preload(),
                                Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->label(__('Linked user'))
                                    ->searchable()
                                    ->preload(),
                                Toggle::make('is_active')
                                    ->label(__('Active'))
                                    ->default(true)
                                    ->required(),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
