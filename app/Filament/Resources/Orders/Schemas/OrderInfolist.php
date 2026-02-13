<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Order;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Order Information'))
                    ->schema([
                        TextEntry::make('order_number')
                            ->label('Order Number'),
                        TextEntry::make('type')
                            ->label('Order Type'),
                        TextEntry::make('customer.name')
                            ->label('Customer')
                            ->placeholder('-'),
                        TextEntry::make('supplier.company_name')
                            ->label('Supplier')
                            ->placeholder('-'),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge(),
                        TextEntry::make('order_date')
                            ->label('Order Date')
                            ->date(),
                        TextEntry::make('delivery_date')
                            ->label('Delivery Date')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('total_amount')
                            ->label('Total Amount')
                            ->money('HUF'),
                        TextEntry::make('shipping_address')
                            ->label('Shipping Address')
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
                                    ->label('Product'),
                                TextEntry::make('quantity')
                                    ->label('Quantity')
                                    ->numeric(),
                                TextEntry::make('unit_price')
                                    ->label('Unit Price')
                                    ->money('HUF'),
                                TextEntry::make('discount_percent')
                                    ->label('Discount')
                                    ->suffix('%'),
                                TextEntry::make('subtotal')
                                    ->label('Subtotal')
                                    ->state(fn ($record): string => number_format($record->calculateSubtotal(), 2).' HUF'),
                                TextEntry::make('note')
                                    ->label('Note')
                                    ->placeholder('-'),
                            ])
                            ->columns(3),
                    ]),

                Section::make(__('Document History'))
                    ->schema([
                        RepeatableEntry::make('receipts')
                            ->label('')
                            ->schema([
                                TextEntry::make('receipt_number')
                                    ->label('Receipt Number'),
                                TextEntry::make('receipt_date')
                                    ->label('Receipt Date')
                                    ->date(),
                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge(),
                                TextEntry::make('total_amount')
                                    ->label('Total Amount')
                                    ->money('HUF'),
                            ])
                            ->columns(4)
                            ->placeholder(__('No receipts yet')),
                    ]),

                Section::make(__('Timestamps'))
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label('Updated At')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('deleted_at')
                            ->label('Deleted At')
                            ->dateTime()
                            ->visible(fn (Order $record): bool => $record->trashed()),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }
}
