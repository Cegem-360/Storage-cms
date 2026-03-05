<?php

declare(strict_types=1);

namespace App\Filament\Resources\Invoices\Schemas;

use App\Models\Invoice;
use App\Models\Team;
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
                Section::make('invoice_information')
                    ->schema([
                        TextEntry::make('invoice_number'),
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
                            ->date(),
                        TextEntry::make('due_date')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('currency'),
                        TextEntry::make('payment_method')
                            ->placeholder('-'),
                        TextEntry::make('notes')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('invoice_items')
                    ->schema([
                        RepeatableEntry::make('invoiceLines')
                            ->label('')
                            ->schema([
                                TextEntry::make('product.name')
                                    ->label(__('Product')),
                                TextEntry::make('quantity')
                                    ->numeric(),
                                TextEntry::make('unit_price')
                                    ->money(Team::currency()),
                                TextEntry::make('discount_percent')
                                    ->label(__('Discount'))
                                    ->suffix('%'),
                                TextEntry::make('tax_percent')
                                    ->label(__('Tax %'))
                                    ->suffix('%'),
                                TextEntry::make('subtotal')
                                    ->state(fn ($record): string => Number::currency((float) $record->subtotal, in: Team::currency(), locale: 'hu')),
                                TextEntry::make('tax_amount')
                                    ->state(fn ($record): string => Number::currency((float) $record->tax_amount, in: Team::currency(), locale: 'hu')),
                                TextEntry::make('line_total')
                                    ->state(fn ($record): string => Number::currency((float) $record->line_total, in: Team::currency(), locale: 'hu')),
                                TextEntry::make('note')
                                    ->placeholder('-'),
                            ])
                            ->columns(3),
                    ]),

                Section::make('invoice_totals')
                    ->schema([
                        TextEntry::make('calculated_subtotal')
                            ->label(__('Subtotal'))
                            ->state(fn (Invoice $record): string => Number::currency(
                                $record->calculated_subtotal,
                                in: Team::currency(),
                                locale: 'hu',
                            )),
                        TextEntry::make('calculated_tax_total')
                            ->label(__('Tax Total'))
                            ->state(fn (Invoice $record): string => Number::currency(
                                $record->calculated_tax_total,
                                in: Team::currency(),
                                locale: 'hu',
                            )),
                        TextEntry::make('calculated_total')
                            ->label(__('Total'))
                            ->state(fn (Invoice $record): string => Number::currency(
                                $record->calculated_total,
                                in: Team::currency(),
                                locale: 'hu',
                            ))
                            ->weight('bold'),
                    ])
                    ->columns(3),

                Section::make('timestamps')
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('deleted_at')
                            ->dateTime()
                            ->visible(fn (Invoice $record): bool => $record->trashed()),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }
}
