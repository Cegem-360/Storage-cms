<?php

declare(strict_types=1);

namespace App\Filament\Resources\Invoices\Schemas;

use App\Models\Invoice;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Number;

final class InvoiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Invoice Information'))
                    ->schema([
                        TextEntry::make('invoice_number')
                            ->label(__('Invoice Number')),
                        TextEntry::make('order.order_number')
                            ->label(__('Order'))
                            ->placeholder('-'),
                        TextEntry::make('receipt.receipt_number')
                            ->label(__('Receipt'))
                            ->placeholder('-'),
                        TextEntry::make('supplier.company_name')
                            ->label(__('Supplier'))
                            ->placeholder('-'),
                        TextEntry::make('customer.name')
                            ->label(__('Customer'))
                            ->placeholder('-'),
                        TextEntry::make('issuedBy.first_name')
                            ->label(__('Issued By'))
                            ->placeholder('-'),
                        TextEntry::make('invoice_date')
                            ->label(__('Invoice Date'))
                            ->date(),
                        TextEntry::make('due_date')
                            ->label(__('Due Date'))
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('status')
                            ->label(__('Status'))
                            ->badge(),
                        TextEntry::make('currency')
                            ->label(__('Currency')),
                        TextEntry::make('payment_method')
                            ->label(__('Payment Method'))
                            ->placeholder('-'),
                        TextEntry::make('notes')
                            ->label(__('Notes'))
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make(__('Invoice Items'))
                    ->schema([
                        RepeatableEntry::make('invoiceLines')
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
                                    ->label(__('Subtotal'))
                                    ->state(fn ($record): string => Number::currency((float) $record->subtotal, in: 'HUF', locale: 'hu')),
                                TextEntry::make('tax_amount')
                                    ->label(__('Tax Amount'))
                                    ->state(fn ($record): string => Number::currency((float) $record->tax_amount, in: 'HUF', locale: 'hu')),
                                TextEntry::make('line_total')
                                    ->label(__('Line Total'))
                                    ->state(fn ($record): string => Number::currency((float) $record->line_total, in: 'HUF', locale: 'hu')),
                                TextEntry::make('note')
                                    ->label(__('Note'))
                                    ->placeholder('-'),
                            ])
                            ->columns(3),
                    ]),

                Section::make(__('Invoice Totals'))
                    ->schema([
                        TextEntry::make('calculated_subtotal')
                            ->label(__('Subtotal'))
                            ->state(fn (Invoice $record): string => Number::currency(
                                $record->calculated_subtotal,
                                in: 'HUF',
                                locale: 'hu',
                            )),
                        TextEntry::make('calculated_tax_total')
                            ->label(__('Tax Total'))
                            ->state(fn (Invoice $record): string => Number::currency(
                                $record->calculated_tax_total,
                                in: 'HUF',
                                locale: 'hu',
                            )),
                        TextEntry::make('calculated_total')
                            ->label(__('Total'))
                            ->state(fn (Invoice $record): string => Number::currency(
                                $record->calculated_total,
                                in: 'HUF',
                                locale: 'hu',
                            ))
                            ->weight('bold'),
                    ])
                    ->columns(3),

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
                            ->visible(fn (Invoice $record): bool => $record->trashed()),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }
}
