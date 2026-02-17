<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Product;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Number;

final class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->tabs([
                        Tab::make('Order Information')
                            ->schema([
                                TextInput::make('order_number')
                                    ->label('Order Number')
                                    ->default(fn (): string => 'ORD-'.now()->format('Ymd').'-'.mb_strtoupper(mb_substr(bin2hex(random_bytes(3)), 0, 6)))
                                    ->required(),
                                Select::make('type')
                                    ->label('Order Type')
                                    ->options(OrderType::class)
                                    ->enum(OrderType::class)
                                    ->required(),
                                Select::make('customer_id')
                                    ->label('Customer')
                                    ->relationship('customer', 'name')
                                    ->searchable()
                                    ->preload(),
                                Select::make('supplier_id')
                                    ->label('Supplier')
                                    ->relationship('supplier', 'company_name')
                                    ->searchable()
                                    ->preload(),
                                Select::make('status')
                                    ->label('Status')
                                    ->options(OrderStatus::class)
                                    ->default(OrderStatus::DRAFT)
                                    ->required(),
                                DatePicker::make('order_date')
                                    ->label('Order Date')
                                    ->required(),
                                DatePicker::make('delivery_date')
                                    ->label('Delivery Date'),
                                Textarea::make('shipping_address')
                                    ->label('Shipping Address')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Tab::make('Order Items')
                            ->schema([
                                Repeater::make('orderLines')
                                    ->label('')
                                    ->relationship()
                                    ->schema([
                                        Select::make('product_id')
                                            ->label('Product')
                                            ->relationship('product', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(function (?string $state, Set $set): void {
                                                if ($state) {
                                                    $product = Product::query()->find($state);
                                                    if ($product) {
                                                        $set('unit_price', (string) $product->price);
                                                    }
                                                }
                                            })
                                            ->columnSpan(3),

                                        TextInput::make('quantity')
                                            ->label('Quantity')
                                            ->numeric()
                                            ->required()
                                            ->default(1)
                                            ->minValue(1)
                                            ->live(debounce: 500)
                                            ->columnSpan(1),

                                        TextInput::make('unit_price')
                                            ->label('Unit Price')
                                            ->numeric()
                                            ->required()
                                            ->default(0)
                                            ->prefix('HUF')
                                            ->live(debounce: 500)
                                            ->columnSpan(1),

                                        TextInput::make('discount_percent')
                                            ->label('Discount')
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->suffix('%')
                                            ->live(debounce: 500)
                                            ->columnSpan(1),

                                        TextEntry::make('line_total')
                                            ->label('Subtotal')
                                            ->state(function (Get $get): string {
                                                $quantity = (float) ($get('quantity') ?? 0);
                                                $unitPrice = (float) ($get('unit_price') ?? 0);
                                                $discount = (float) ($get('discount_percent') ?? 0);

                                                return Number::currency($quantity * $unitPrice * (1 - $discount / 100), in: 'HUF', locale: 'hu');
                                            })
                                            ->columnSpan(2),

                                        Textarea::make('note')
                                            ->label('Note')
                                            ->columnSpan(4),
                                    ])
                                    ->columns(4)
                                    ->defaultItems(1)
                                    ->reorderable(false)
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['product_id']
                                        ? Product::query()->find($state['product_id'])?->name
                                        : null
                                    ),
                            ]),

                        Tab::make('Summary')
                            ->schema([
                                TextEntry::make('calculated_total')
                                    ->label('Total')
                                    ->state(fn ($record): string => Number::currency(
                                        $record?->calculated_total ?? 0,
                                        in: 'HUF',
                                        locale: 'hu',
                                    )),
                            ])
                            ->visibleOn(['edit', 'view']),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
