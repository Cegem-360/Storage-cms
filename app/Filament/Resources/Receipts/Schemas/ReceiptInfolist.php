<?php

declare(strict_types=1);

namespace App\Filament\Resources\Receipts\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class ReceiptInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Receipt Information'))
                    ->schema([
                        TextEntry::make('receipt_number')
                            ->label(__('Receipt Number')),
                        TextEntry::make('order.order_number')
                            ->label(__('Order'))
                            ->placeholder('-'),
                        TextEntry::make('warehouse.name')
                            ->label(__('Warehouse')),
                        TextEntry::make('receivedBy.first_name')
                            ->label(__('Received By'))
                            ->placeholder('-'),
                        TextEntry::make('receipt_date')
                            ->label(__('Receipt Date'))
                            ->date(),
                        TextEntry::make('status')
                            ->label(__('Status'))
                            ->badge(),
                        TextEntry::make('total_amount')
                            ->label(__('Total Amount'))
                            ->money('HUF'),
                        TextEntry::make('notes')
                            ->label(__('Notes'))
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make(__('Receipt Items'))
                    ->schema([
                        RepeatableEntry::make('receiptLines')
                            ->label('')
                            ->schema([
                                TextEntry::make('product.name')
                                    ->label(__('Product')),
                                TextEntry::make('quantity_expected')
                                    ->label(__('Expected Qty'))
                                    ->numeric(),
                                TextEntry::make('quantity_received')
                                    ->label(__('Received Qty'))
                                    ->numeric(),
                                TextEntry::make('variance')
                                    ->label(__('Variance'))
                                    ->state(fn ($record): int => $record->calculateVariance())
                                    ->numeric()
                                    ->color(fn ($record): string => $record->isDiscrepant() ? 'danger' : 'success'),
                                TextEntry::make('unit_price')
                                    ->label(__('Unit Price'))
                                    ->money('HUF'),
                                TextEntry::make('condition')
                                    ->label(__('Condition'))
                                    ->badge(),
                                TextEntry::make('batch_number')
                                    ->label(__('Batch Number'))
                                    ->placeholder('-'),
                                TextEntry::make('expiry_date')
                                    ->label(__('Expiry Date'))
                                    ->date()
                                    ->placeholder('-'),
                                TextEntry::make('note')
                                    ->label(__('Note'))
                                    ->placeholder('-'),
                            ])
                            ->columns(3),
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
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }
}
