<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Order;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Number;

final class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Order Information'))
                    ->schema([
                        TextEntry::make('order_number')
                            ->label(__('Order Number')),
                        TextEntry::make('type')
                            ->label(__('Order Type')),
                        TextEntry::make('customer.name')
                            ->label(__('Customer'))
                            ->placeholder('-'),
                        TextEntry::make('supplier.company_name')
                            ->label(__('Supplier'))
                            ->placeholder('-'),
                        TextEntry::make('status')
                            ->label(__('Status'))
                            ->badge(),
                        TextEntry::make('order_date')
                            ->label(__('Order Date'))
                            ->date(),
                        TextEntry::make('delivery_date')
                            ->label(__('Delivery Date'))
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('total_amount')
                            ->label(__('Total Amount'))
                            ->money('HUF'),
                        TextEntry::make('shipping_address')
                            ->label(__('Shipping Address'))
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make(__('Order Items'))
                    ->schema([
                        RepeatableEntry::make('orderLines')
                            ->label('')
                            ->schema([
                                TextEntry::make('product.name')
                                    ->label(__('Product')),
                                TextEntry::make('quantity')
                                    ->label(__('Quantity'))
                                    ->numeric(),
                                TextEntry::make('unit_price')
                                    ->label(__('Unit Price'))
                                    ->money('HUF'),
                                TextEntry::make('discount_percent')
                                    ->label(__('Discount'))
                                    ->suffix('%'),
                                TextEntry::make('tax_percent')
                                    ->label(__('Tax %'))
                                    ->suffix('%'),
                                TextEntry::make('subtotal')
                                    ->label(__('Net Total'))
                                    ->state(fn ($record): string => Number::currency($record->subtotal, in: 'HUF', locale: 'hu')),
                                TextEntry::make('tax_amount')
                                    ->label(__('Tax Amount'))
                                    ->state(fn ($record): string => Number::currency($record->tax_amount, in: 'HUF', locale: 'hu')),
                                TextEntry::make('total_with_tax')
                                    ->label(__('Gross Total'))
                                    ->state(fn ($record): string => Number::currency($record->total_with_tax, in: 'HUF', locale: 'hu')),
                                TextEntry::make('note')
                                    ->label(__('Note'))
                                    ->placeholder('-'),
                            ])
                            ->columns(3),
                    ]),

                Section::make(__('Order Totals'))
                    ->schema([
                        TextEntry::make('calculated_net_total')
                            ->label(__('Net Total'))
                            ->state(fn (Order $record): string => Number::currency(
                                $record->calculated_net_total,
                                in: 'HUF',
                                locale: 'hu',
                            )),
                        TextEntry::make('calculated_tax_total')
                            ->label(__('Tax Total'))
                            ->state(fn (Order $record): string => Number::currency(
                                $record->calculated_tax_total,
                                in: 'HUF',
                                locale: 'hu',
                            )),
                        TextEntry::make('calculated_total')
                            ->label(__('Gross Total'))
                            ->state(fn (Order $record): string => Number::currency(
                                $record->calculated_total,
                                in: 'HUF',
                                locale: 'hu',
                            ))
                            ->weight('bold'),
                    ])
                    ->columns(3),

                Section::make(__('Document History'))
                    ->schema([
                        RepeatableEntry::make('receipts')
                            ->label(__('Receipts'))
                            ->schema([
                                TextEntry::make('receipt_number')
                                    ->label(__('Receipt Number')),
                                TextEntry::make('receipt_date')
                                    ->label(__('Date'))
                                    ->date(),
                                TextEntry::make('status')
                                    ->label(__('Status'))
                                    ->badge(),
                                TextEntry::make('total_amount')
                                    ->label(__('Total'))
                                    ->money('HUF'),
                            ])
                            ->columns(4)
                            ->placeholder(__('No receipts yet')),

                        RepeatableEntry::make('returnDeliveries')
                            ->label(__('Return Deliveries'))
                            ->schema([
                                TextEntry::make('return_number')
                                    ->label(__('Return Number')),
                                TextEntry::make('return_date')
                                    ->label(__('Date'))
                                    ->date(),
                                TextEntry::make('status')
                                    ->label(__('Status'))
                                    ->badge(),
                                TextEntry::make('total_amount')
                                    ->label(__('Total'))
                                    ->money('HUF'),
                            ])
                            ->columns(4)
                            ->placeholder(__('No return deliveries yet')),

                        RepeatableEntry::make('invoices')
                            ->label(__('Invoices'))
                            ->schema([
                                TextEntry::make('invoice_number')
                                    ->label(__('Invoice Number')),
                                TextEntry::make('invoice_date')
                                    ->label(__('Date'))
                                    ->date(),
                                TextEntry::make('due_date')
                                    ->label(__('Due Date'))
                                    ->date()
                                    ->placeholder('-'),
                                TextEntry::make('status')
                                    ->label(__('Status'))
                                    ->badge(),
                                TextEntry::make('total_amount')
                                    ->label(__('Total'))
                                    ->money('HUF'),
                            ])
                            ->columns(5)
                            ->placeholder(__('No invoices yet')),
                    ]),

                Section::make(__('Timestamps'))
                    ->schema([
                        TextEntry::make('created_at')
                            ->label(__('Created At'))
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label(__('Updated At'))
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('deleted_at')
                            ->label(__('Deleted At'))
                            ->dateTime()
                            ->visible(fn (Order $record): bool => $record->trashed()),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }
}
