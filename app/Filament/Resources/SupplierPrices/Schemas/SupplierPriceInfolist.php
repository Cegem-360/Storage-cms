<?php

declare(strict_types=1);

namespace App\Filament\Resources\SupplierPrices\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class SupplierPriceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Price Information'))
                    ->schema([
                        TextEntry::make('supplier.company_name')
                            ->label(__('Supplier')),
                        TextEntry::make('product.name')
                            ->label(__('Product')),
                        TextEntry::make('price')
                            ->label(__('Price'))
                            ->money('HUF'),
                        TextEntry::make('currency')
                            ->label(__('Currency')),
                        TextEntry::make('minimum_order_quantity')
                            ->label(__('Min. Order Quantity'))
                            ->numeric(),
                        TextEntry::make('lead_time_days')
                            ->label(__('Lead Time'))
                            ->suffix(' '.__('days')),
                    ])
                    ->columns(2),

                Section::make(__('Validity'))
                    ->schema([
                        TextEntry::make('valid_from')
                            ->label(__('Valid From'))
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('valid_until')
                            ->label(__('Valid Until'))
                            ->date()
                            ->placeholder('-'),
                        IconEntry::make('is_active')
                            ->label(__('Active'))
                            ->boolean(),
                        TextEntry::make('notes')
                            ->label(__('Notes'))
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make(__('Quantity Discount Tiers'))
                    ->schema([
                        RepeatableEntry::make('tiers')
                            ->label('')
                            ->schema([
                                TextEntry::make('min_quantity')
                                    ->label(__('Min. Quantity'))
                                    ->numeric(),
                                TextEntry::make('max_quantity')
                                    ->label(__('Max. Quantity'))
                                    ->numeric()
                                    ->placeholder('∞'),
                                TextEntry::make('price')
                                    ->label(__('Price'))
                                    ->money('HUF'),
                            ])
                            ->columns(3),
                    ])
                    ->visible(fn ($record): bool => $record->tiers->isNotEmpty()),

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
