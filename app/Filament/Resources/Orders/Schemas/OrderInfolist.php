<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Order;
use App\Models\Team;
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
                Section::make('order_information')
                    ->schema([
                        TextEntry::make('order_number'),
                        TextEntry::make('type')
                            ->label(__('Order Type')),
                        TextEntry::make('customer.name')
                            ->label(__('Customer'))
                            ->placeholder('-'),
                        TextEntry::make('supplier.company_name')
                            ->label(__('Supplier'))
                            ->placeholder('-'),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('order_date')
                            ->date(),
                        TextEntry::make('delivery_date')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('total_amount')
                            ->money(Team::currency()),
                        TextEntry::make('shipping_address')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('order_items')
                    ->schema([
                        RepeatableEntry::make('orderLines')
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
                                    ->label(__('Net Total'))
                                    ->state(fn ($record): string => Number::currency($record->subtotal, in: Team::currency(), locale: 'hu')),
                                TextEntry::make('tax_amount')
                                    ->state(fn ($record): string => Number::currency($record->tax_amount, in: Team::currency(), locale: 'hu')),
                                TextEntry::make('total_with_tax')
                                    ->label(__('Gross Total'))
                                    ->state(fn ($record): string => Number::currency($record->total_with_tax, in: Team::currency(), locale: 'hu')),
                                TextEntry::make('note')
                                    ->placeholder('-'),
                            ])
                            ->columns(3),
                    ]),

                Section::make('order_totals')
                    ->schema([
                        TextEntry::make('calculated_net_total')
                            ->label(__('Net Total'))
                            ->state(fn (Order $record): string => Number::currency(
                                $record->calculated_net_total,
                                in: Team::currency(),
                                locale: 'hu',
                            )),
                        TextEntry::make('calculated_tax_total')
                            ->label(__('Tax Total'))
                            ->state(fn (Order $record): string => Number::currency(
                                $record->calculated_tax_total,
                                in: Team::currency(),
                                locale: 'hu',
                            )),
                        TextEntry::make('calculated_total')
                            ->label(__('Gross Total'))
                            ->state(fn (Order $record): string => Number::currency(
                                $record->calculated_total,
                                in: Team::currency(),
                                locale: 'hu',
                            ))
                            ->weight('bold'),
                    ])
                    ->columns(3),

                Section::make('document_history')
                    ->schema([
                        RepeatableEntry::make('receipts')
                            ->schema([
                                TextEntry::make('receipt_number'),
                                TextEntry::make('receipt_date')
                                    ->label(__('Date'))
                                    ->date(),
                                TextEntry::make('status')
                                    ->badge(),
                                TextEntry::make('total_amount')
                                    ->label(__('Total'))
                                    ->money(Team::currency()),
                            ])
                            ->columns(4)
                            ->placeholder(__('No receipts yet')),

                        RepeatableEntry::make('returnDeliveries')
                            ->schema([
                                TextEntry::make('return_number'),
                                TextEntry::make('return_date')
                                    ->label(__('Date'))
                                    ->date(),
                                TextEntry::make('status')
                                    ->badge(),
                                TextEntry::make('total_amount')
                                    ->label(__('Total'))
                                    ->money(Team::currency()),
                            ])
                            ->columns(4)
                            ->placeholder(__('No return deliveries yet')),

                        RepeatableEntry::make('invoices')
                            ->schema([
                                TextEntry::make('invoice_number'),
                                TextEntry::make('invoice_date')
                                    ->label(__('Date'))
                                    ->date(),
                                TextEntry::make('due_date')
                                    ->date()
                                    ->placeholder('-'),
                                TextEntry::make('status')
                                    ->badge(),
                                TextEntry::make('total_amount')
                                    ->label(__('Total'))
                                    ->money(Team::currency()),
                            ])
                            ->columns(5)
                            ->placeholder(__('No invoices yet')),
                    ]),

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
                            ->visible(fn (Order $record): bool => $record->trashed()),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }
}
