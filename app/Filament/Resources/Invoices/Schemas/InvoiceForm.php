<?php

declare(strict_types=1);

namespace App\Filament\Resources\Invoices\Schemas;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderLine;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Number;

final class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->tabs([
                        Tab::make(__('Invoice Information'))
                            ->schema([
                                TextInput::make('invoice_number')
                                    ->label(__('Invoice Number'))
                                    ->default(fn (): string => 'INV-'.now()->format('Ymd').'-'.mb_strtoupper(mb_substr(bin2hex(random_bytes(3)), 0, 6)))
                                    ->required(),
                                Select::make('order_id')
                                    ->label(__('Order'))
                                    ->relationship('order', 'order_number')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function (?string $state, Set $set): void {
                                        if (! $state) {
                                            return;
                                        }

                                        $order = Order::with('orderLines.product')->find($state);

                                        if (! $order) {
                                            return;
                                        }

                                        $lines = $order->orderLines->map(fn (OrderLine $line): array => [
                                            'product_id' => $line->product_id,
                                            'quantity' => $line->quantity,
                                            'unit_price' => $line->unit_price,
                                            'discount_percent' => $line->discount_percent,
                                            'tax_percent' => $line->tax_percent,
                                            'note' => $line->note,
                                        ])->toArray();

                                        $set('invoiceLines', $lines);

                                        $set('customer_id', $order->customer_id);
                                        $set('supplier_id', $order->supplier_id);
                                    }),
                                Select::make('receipt_id')
                                    ->label(__('Receipt'))
                                    ->relationship('receipt', 'receipt_number')
                                    ->searchable()
                                    ->preload(),
                                Select::make('supplier_id')
                                    ->label(__('Supplier'))
                                    ->relationship('supplier', 'company_name')
                                    ->searchable()
                                    ->preload(),
                                Select::make('customer_id')
                                    ->label(__('Customer'))
                                    ->relationship('customer', 'name')
                                    ->searchable()
                                    ->preload(),
                                Select::make('issued_by')
                                    ->label(__('Issued By'))
                                    ->relationship('issuedBy', 'first_name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                DatePicker::make('invoice_date')
                                    ->label(__('Invoice Date'))
                                    ->default(now())
                                    ->required(),
                                DatePicker::make('due_date')
                                    ->label(__('Due Date'))
                                    ->default(now()->addDays(30)),
                                Select::make('status')
                                    ->label(__('Status'))
                                    ->options(InvoiceStatus::class)
                                    ->default(InvoiceStatus::DRAFT)
                                    ->required(),
                                TextInput::make('currency')
                                    ->label(__('Currency'))
                                    ->default('HUF')
                                    ->maxLength(3)
                                    ->required(),
                                TextInput::make('payment_method')
                                    ->label(__('Payment Method')),
                                Textarea::make('notes')
                                    ->label(__('Notes'))
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Tab::make(__('Invoice Items'))
                            ->schema([
                                Repeater::make('invoiceLines')
                                    ->label('')
                                    ->relationship()
                                    ->schema([
                                        Select::make('product_id')
                                            ->label(__('Product'))
                                            ->relationship('product', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->columnSpan(2),
                                        TextInput::make('quantity')
                                            ->label(__('Quantity'))
                                            ->numeric()
                                            ->required()
                                            ->default(1)
                                            ->minValue(1),
                                        TextInput::make('unit_price')
                                            ->label(__('Unit Price'))
                                            ->numeric()
                                            ->required()
                                            ->default(0)
                                            ->prefix('HUF'),
                                        TextInput::make('discount_percent')
                                            ->label(__('Discount %'))
                                            ->numeric()
                                            ->default(0)
                                            ->suffix('%'),
                                        TextInput::make('tax_percent')
                                            ->label(__('Tax %'))
                                            ->numeric()
                                            ->default(27)
                                            ->suffix('%'),
                                        Textarea::make('note')
                                            ->label(__('Note'))
                                            ->columnSpan(2),
                                    ])
                                    ->columns(4)
                                    ->defaultItems(1)
                                    ->reorderable(false)
                                    ->collapsible(),
                            ]),

                        Tab::make(__('Summary'))
                            ->schema([
                                TextEntry::make('calculated_subtotal')
                                    ->label(__('Subtotal'))
                                    ->state(fn (Invoice $record): string => Number::currency(
                                        $record?->calculated_subtotal ?? 0,
                                        in: 'HUF',
                                        locale: 'hu',
                                    )),
                                TextEntry::make('calculated_tax_total')
                                    ->label(__('Tax Total'))
                                    ->state(fn (Invoice $record): string => Number::currency(
                                        $record?->calculated_tax_total ?? 0,
                                        in: 'HUF',
                                        locale: 'hu',
                                    )),
                                TextEntry::make('calculated_total')
                                    ->label(__('Total'))
                                    ->state(fn (Invoice $record): string => Number::currency(
                                        $record?->calculated_total ?? 0,
                                        in: 'HUF',
                                        locale: 'hu',
                                    ))
                                    ->weight('bold'),
                            ])
                            ->columns(3)
                            ->visibleOn(['edit', 'view']),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
