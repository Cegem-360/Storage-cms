<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Product;
use Filament\Forms\Components\Component;
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
                        Tab::make(__('Order Information'))
                            ->schema(self::getOrderInfoFields()),

                        Tab::make(__('Order Items'))
                            ->schema([
                                self::getOrderLineRepeater(),
                            ]),

                        Tab::make(__('Summary'))
                            ->schema(self::getSummaryFields())
                            ->visibleOn(['edit', 'view']),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    /**
     * @return array<int, Component>
     */
    public static function getOrderInfoFields(): array
    {
        return [
            TextInput::make('order_number')
                ->label(__('Order Number'))
                ->default(fn (): string => 'ORD-'.now()->format('Ymd').'-'.mb_strtoupper(mb_substr(bin2hex(random_bytes(3)), 0, 6)))
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
        ];
    }

    public static function getOrderLineRepeater(): Repeater
    {
        return Repeater::make('orderLines')
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
                            $product = Product::query()->find($state);
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

                Select::make('tax_percent')
                    ->label(__('Tax %'))
                    ->options([
                        '0' => '0%',
                        '5' => '5%',
                        '18' => '18%',
                        '27' => '27%',
                    ])
                    ->default('27')
                    ->required()
                    ->live()
                    ->columnSpan(1),

                TextEntry::make('line_total')
                    ->label(__('Gross Total'))
                    ->state(function (Get $get): string {
                        $quantity = (float) ($get('quantity') ?? 0);
                        $unitPrice = (float) ($get('unit_price') ?? 0);
                        $discount = (float) ($get('discount_percent') ?? 0);
                        $tax = (float) ($get('tax_percent') ?? 27);
                        $net = $quantity * $unitPrice * (1 - $discount / 100);

                        return Number::currency($net * (1 + $tax / 100), in: 'HUF', locale: 'hu');
                    })
                    ->columnSpan(1),

                Textarea::make('note')
                    ->label(__('Note'))
                    ->columnSpan(8),
            ])
            ->columns(8)
            ->defaultItems(1)
            ->reorderable(false)
            ->collapsible()
            ->itemLabel(fn (array $state): ?string => $state['product_id']
                ? Product::query()->find($state['product_id'])?->name
                : null
            );
    }

    /**
     * @return array<int, Component>
     */
    public static function getShippingFields(): array
    {
        return [
            Textarea::make('shipping_address')
                ->label(__('Shipping Address'))
                ->columnSpanFull(),
        ];
    }

    /**
     * @return array<int, \Filament\Schemas\Components\Component>
     */
    public static function getSummaryFields(): array
    {
        return [
            Section::make(__('Order Totals'))
                ->schema([
                    TextEntry::make('calculated_net_total')
                        ->label(__('Net Total'))
                        ->state(fn ($record): string => Number::currency(
                            $record?->calculated_net_total ?? 0,
                            in: 'HUF',
                            locale: 'hu',
                        )),
                    TextEntry::make('calculated_tax_total')
                        ->label(__('Tax Total'))
                        ->state(fn ($record): string => Number::currency(
                            $record?->calculated_tax_total ?? 0,
                            in: 'HUF',
                            locale: 'hu',
                        )),
                    TextEntry::make('calculated_total')
                        ->label(__('Gross Total'))
                        ->state(fn ($record): string => Number::currency(
                            $record?->calculated_total ?? 0,
                            in: 'HUF',
                            locale: 'hu',
                        ))
                        ->weight('bold'),
                ])
                ->columns(3),
        ];
    }
}
