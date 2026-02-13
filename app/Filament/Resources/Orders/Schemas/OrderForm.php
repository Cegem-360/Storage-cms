<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Product;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

final class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Order Information'))
                    ->schema([
                        TextInput::make('order_number')
                            ->label(__('Order Number'))
                            ->default(fn () => 'ORD-'.now()->format('Ymd').'-'.mb_strtoupper(mb_substr(bin2hex(random_bytes(3)), 0, 6)))
                            ->required(),
                        Select::make('type')
                            ->label(__('Order Type'))
                            ->options(OrderType::class)
                            ->enum(OrderType::class)
                            ->required(),
                        Select::make('customer_id')
                            ->label(__('Customer'))
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('supplier_id')
                            ->label(__('Supplier'))
                            ->relationship('supplier', 'company_name')
                            ->searchable()
                            ->preload(),
                        Select::make('status')
                            ->label(__('Status'))
                            ->options(OrderStatus::class)
                            ->default(OrderStatus::DRAFT)
                            ->required(),
                        DatePicker::make('order_date')
                            ->label(__('Order Date'))
                            ->required(),
                        DatePicker::make('delivery_date')
                            ->label(__('Delivery Date')),
                        Textarea::make('shipping_address')
                            ->label(__('Shipping Address'))
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make(__('Order Items'))
                    ->schema([
                        Repeater::make('orderLines')
                            ->label('')
                            ->relationship()
                            ->schema([
                                Select::make('product_id')
                                    ->label(__('Product'))
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (?string $state, Set $set): void {
                                        if ($state) {
                                            $product = Product::find($state);
                                            if ($product) {
                                                $set('unit_price', (string) $product->price);
                                            }
                                        }
                                    })
                                    ->columnSpan(3),

                                TextInput::make('quantity')
                                    ->label(__('Quantity'))
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->minValue(1)
                                    ->live(debounce: 500)
                                    ->columnSpan(1),

                                TextInput::make('unit_price')
                                    ->label(__('Unit Price'))
                                    ->numeric()
                                    ->required()
                                    ->default(0)
                                    ->prefix('HUF')
                                    ->live(debounce: 500)
                                    ->columnSpan(1),

                                TextInput::make('discount_percent')
                                    ->label(__('Discount'))
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->suffix('%')
                                    ->live(debounce: 500)
                                    ->columnSpan(1),

                                Placeholder::make('line_total')
                                    ->label(__('Subtotal'))
                                    ->content(function (Get $get): string {
                                        $quantity = (float) ($get('quantity') ?? 0);
                                        $unitPrice = (float) ($get('unit_price') ?? 0);
                                        $discount = (float) ($get('discount_percent') ?? 0);

                                        return number_format($quantity * $unitPrice * (1 - $discount / 100), 2).' HUF';
                                    })
                                    ->columnSpan(2),

                                Textarea::make('note')
                                    ->label(__('Note'))
                                    ->columnSpan(4),
                            ])
                            ->columns(4)
                            ->defaultItems(1)
                            ->reorderable(false)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['product_id']
                                ? Product::find($state['product_id'])?->name
                                : null
                            ),
                    ]),

                Section::make(__('Summary'))
                    ->schema([
                        Placeholder::make('calculated_total')
                            ->label(__('Total'))
                            ->content(fn ($record): string => $record
                                ? number_format((float) $record->calculateTotal(), 2).' HUF'
                                : '0.00 HUF'
                            ),
                    ])
                    ->visibleOn(['edit', 'view']),
            ]);
    }
}
