<?php

declare(strict_types=1);

namespace App\Filament\Resources\ReturnDeliveries\Schemas;

use App\Models\ReturnDelivery;
use App\Models\Team;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class ReturnDeliveryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('return_information')
                    ->schema([
                        TextEntry::make('return_number'),

                        TextEntry::make('type')
                            ->label(__('Return Type'))
                            ->badge(),

                        TextEntry::make('status')
                            ->badge(),

                        TextEntry::make('reason')
                            ->badge(),

                        TextEntry::make('warehouse.name')
                            ->label(__('Warehouse')),

                        TextEntry::make('return_date')
                            ->date(),

                        TextEntry::make('processedBy.first_name')
                            ->label(__('Processed By'))
                            ->formatStateUsing(fn (ReturnDelivery $record): string => $record->processedBy
                                ? $record->processedBy->first_name.' '.$record->processedBy->last_name
                                : '-'),
                    ])
                    ->columns(3),

                Section::make('related_records')
                    ->schema([
                        TextEntry::make('order.order_number')
                            ->label(__('Related Order'))
                            ->placeholder('-')
                            ->visible(fn ($record) => $record->isCustomerReturn()),

                        TextEntry::make('order.customer.name')
                            ->label(__('Customer'))
                            ->placeholder('-')
                            ->visible(fn ($record) => $record->isCustomerReturn()),

                        TextEntry::make('order.supplier.company_name')
                            ->label(__('Supplier'))
                            ->placeholder('-')
                            ->visible(fn ($record) => $record->isSupplierReturn()),
                    ])
                    ->columns(2)
                    ->visible(fn ($record): bool => $record->order_id || $record->customer_id || $record->supplier_id),

                Section::make('return_items')
                    ->schema([
                        RepeatableEntry::make('returnDeliveryLines')
                            ->label('')
                            ->schema([
                                TextEntry::make('product.name')
                                    ->label(__('Product')),

                                TextEntry::make('quantity')
                                    ->numeric(),

                                TextEntry::make('unit_price')
                                    ->money(Team::currency()),

                                TextEntry::make('condition')
                                    ->badge(),

                                TextEntry::make('return_reason')
                                    ->badge(),

                                TextEntry::make('batch_number')
                                    ->placeholder('-'),

                                TextEntry::make('note')
                                    ->placeholder('-')
                                    ->columnSpanFull(),
                            ])
                            ->columns(3),
                    ]),

                Section::make('additional_information')
                    ->schema([
                        TextEntry::make('total_amount')
                            ->money(Team::currency()),

                        TextEntry::make('notes')
                            ->placeholder('-')
                            ->columnSpanFull(),

                        TextEntry::make('created_at')
                            ->dateTime(),

                        TextEntry::make('updated_at')
                            ->dateTime(),

                        TextEntry::make('deleted_at')
                            ->dateTime()
                            ->visible(fn (ReturnDelivery $record): bool => $record->trashed()),
                    ])
                    ->columns(2),
            ]);
    }
}
