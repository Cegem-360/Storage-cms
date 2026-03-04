<?php

declare(strict_types=1);

namespace App\Filament\Resources\ReturnDeliveries\Schemas;

use App\Enums\ProductCondition;
use App\Enums\ReturnReason;
use App\Enums\ReturnStatus;
use App\Enums\ReturnType;
use App\Models\Team;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Number;

final class ReturnDeliveryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->tabs([
                        Tab::make(__('Return Details'))
                            ->schema([
                                Section::make(__('Return Information'))
                                    ->schema([
                                        TextInput::make('return_number')
                                            ->label(__('Return Number'))
                                            ->default(fn (): string => 'RET-'.mb_strtoupper(uniqid()))
                                            ->required()
                                            ->maxLength(100)
                                            ->scopedUnique(ignoreRecord: true),

                                        Select::make('type')
                                            ->label(__('Return Type'))
                                            ->options(ReturnType::class)
                                            ->enum(ReturnType::class)
                                            ->required()
                                            ->live()
                                            ->default(ReturnType::CUSTOMER_RETURN),

                                        Select::make('warehouse_id')
                                            ->relationship('warehouse', 'name')
                                            ->label(__('Warehouse'))
                                            ->searchable()
                                            ->preload()
                                            ->required(),

                                        DatePicker::make('return_date')
                                            ->label(__('Return Date'))
                                            ->default(now())
                                            ->required(),

                                        Select::make('status')
                                            ->options(ReturnStatus::class)
                                            ->default(ReturnStatus::DRAFT)
                                            ->required(),

                                        Select::make('reason')
                                            ->options(ReturnReason::class)
                                            ->required(),
                                    ])
                                    ->columns(2),

                                Section::make(__('Related Records'))
                                    ->schema([
                                        Select::make('order_id')
                                            ->relationship('order', 'order_number')
                                            ->label(__('Related Order'))
                                            ->searchable()
                                            ->preload()
                                            ->visible(fn (Get $get): bool => $get('type') === ReturnType::CUSTOMER_RETURN),

                                        Select::make('customer_id')
                                            ->relationship('order.customer', 'name')
                                            ->label(__('Customer'))
                                            ->searchable()
                                            ->preload()
                                            ->visible(fn (Get $get): bool => $get('type') === ReturnType::CUSTOMER_RETURN),

                                        Select::make('supplier_id')
                                            ->relationship('order.supplier', 'company_name')
                                            ->label(__('Supplier'))
                                            ->searchable()
                                            ->preload()
                                            ->visible(fn (Get $get): bool => $get('type') === ReturnType::SUPPLIER_RETURN),

                                        Select::make('processed_by')
                                            ->relationship('processedBy', 'first_name')
                                            ->label(__('Processed By'))
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                    ])
                                    ->columns(2),

                                Textarea::make('notes')
                                    ->label(__('Notes'))
                                    ->columnSpanFull(),
                            ]),

                        Tab::make(__('Return Items'))
                            ->schema([
                                Repeater::make('returnDeliveryLines')
                                    ->relationship()
                                    ->schema([
                                        Select::make('product_id')
                                            ->relationship('product', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->columnSpan(3),

                                        TextInput::make('quantity')
                                            ->numeric()
                                            ->required()
                                            ->default(1)
                                            ->minValue(1)
                                            ->columnSpan(1),

                                        TextInput::make('unit_price')
                                            ->numeric()
                                            ->required()
                                            ->default(0)
                                            ->prefix(Team::currency())
                                            ->columnSpan(2),

                                        Select::make('condition')
                                            ->options(ProductCondition::class)
                                            ->required()
                                            ->default(ProductCondition::GOOD)
                                            ->columnSpan(2),

                                        Select::make('return_reason')
                                            ->options(ReturnReason::class)
                                            ->required()
                                            ->columnSpan(2),

                                        TextInput::make('batch_number')
                                            ->label(__('Batch Number'))
                                            ->columnSpan(2),

                                        Textarea::make('note')
                                            ->label(__('Note'))
                                            ->columnSpan(4),
                                    ])
                                    ->columns(4)
                                    ->defaultItems(1)
                                    ->reorderable(false)
                                    ->collapsible(),
                            ]),

                        Tab::make(__('Summary'))
                            ->schema([
                                TextEntry::make('total_amount')
                                    ->label(__('Total Amount'))
                                    ->state(fn ($record): string => Number::currency(
                                        (float) ($record?->total_amount ?? 0),
                                        in: Team::currency(),
                                        locale: 'hu',
                                    )),
                            ])
                            ->visibleOn('edit'),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
