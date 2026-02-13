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
                            ->label('Receipt Number'),
                        TextEntry::make('order.order_number')
                            ->label('Order')
                            ->placeholder('-'),
                        TextEntry::make('warehouse.name')
                            ->label('Warehouse'),
                        TextEntry::make('receivedBy.first_name')
                            ->label('Received By')
                            ->placeholder('-'),
                        TextEntry::make('receipt_date')
                            ->label('Receipt Date')
                            ->date(),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge(),
                        TextEntry::make('total_amount')
                            ->label('Total Amount')
                            ->money('HUF'),
                        TextEntry::make('notes')
                            ->label('Notes')
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
                                    ->label('Product'),
                                TextEntry::make('quantity_expected')
                                    ->label('Expected Qty')
                                    ->numeric(),
                                TextEntry::make('quantity_received')
                                    ->label('Received Qty')
                                    ->numeric(),
                                TextEntry::make('variance')
                                    ->label('Variance')
                                    ->state(fn ($record): int => $record->calculateVariance())
                                    ->numeric()
                                    ->color(fn ($record): string => $record->isDiscrepant() ? 'danger' : 'success'),
                                TextEntry::make('unit_price')
                                    ->label('Unit Price')
                                    ->money('HUF'),
                                TextEntry::make('condition')
                                    ->label('Condition')
                                    ->badge(),
                                TextEntry::make('batch_number')
                                    ->label('Batch Number')
                                    ->placeholder('-'),
                                TextEntry::make('expiry_date')
                                    ->label('Expiry Date')
                                    ->date()
                                    ->placeholder('-'),
                                TextEntry::make('note')
                                    ->label('Note')
                                    ->placeholder('-'),
                            ])
                            ->columns(3),
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
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }
}
