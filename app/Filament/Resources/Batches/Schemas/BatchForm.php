<?php

declare(strict_types=1);

namespace App\Filament\Resources\Batches\Schemas;

use App\Enums\QualityStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

final class BatchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->tabs([
                        Tab::make('general')
                            ->schema([
                                TextInput::make('batch_number')
                                    ->required()
                                    ->scopedUnique(ignoreRecord: true)
                                    ->maxLength(100),
                                Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                Select::make('supplier_id')
                                    ->label(__('Supplier'))
                                    ->relationship('supplier', 'company_name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                DatePicker::make('manufacture_date'),
                                DatePicker::make('expiry_date'),
                                TextInput::make('quantity')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0),
                                Select::make('quality_status')
                                    ->options(QualityStatus::class)
                                    ->default(QualityStatus::PENDING_CHECK)
                                    ->required(),
                            ])
                            ->columns(2),

                        Tab::make('serial_numbers')
                            ->schema([
                                Repeater::make('serial_numbers')
                                    ->schema([
                                        TextInput::make('serial')
                                            ->label(__('Serial Number'))
                                            ->required(),
                                    ])
                                    ->columns(1)
                                    ->collapsible()
                                    ->defaultItems(0)
                                    ->addActionLabel(__('Add Serial Number')),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
