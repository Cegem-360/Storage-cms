<?php

declare(strict_types=1);

namespace App\Filament\Resources\SupplierPrices\Schemas;

use App\Models\Team;
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
                Section::make('price_information')
                    ->schema([
                        TextEntry::make('supplier.company_name')
                            ->label(__('Supplier')),
                        TextEntry::make('product.name')
                            ->label(__('Product')),
                        TextEntry::make('price')
                            ->money(Team::currency()),
                        TextEntry::make('currency'),
                        TextEntry::make('minimum_order_quantity')
                            ->label(__('Min. Order Quantity'))
                            ->numeric(),
                        TextEntry::make('lead_time_days')
                            ->label(__('Lead Time'))
                            ->suffix(' '.__('days')),
                    ])
                    ->columns(2),

                Section::make('validity')
                    ->schema([
                        TextEntry::make('valid_from')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('valid_until')
                            ->date()
                            ->placeholder('-'),
                        IconEntry::make('is_active')
                            ->label(__('Active'))
                            ->boolean(),
                        TextEntry::make('notes')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('quantity_discount_tiers')
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
                                    ->money(Team::currency()),
                            ])
                            ->columns(3),
                    ])
                    ->visible(fn ($record): bool => $record->tiers->isNotEmpty()),

                Section::make('timestamps')
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }
}
