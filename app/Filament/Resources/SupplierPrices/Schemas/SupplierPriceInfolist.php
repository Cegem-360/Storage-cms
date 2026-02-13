<?php

declare(strict_types=1);

namespace App\Filament\Resources\SupplierPrices\Schemas;

use Filament\Infolists\Components\IconEntry;
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
                            ->label('Supplier'),
                        TextEntry::make('product.name')
                            ->label('Product'),
                        TextEntry::make('price')
                            ->label('Price')
                            ->money('HUF'),
                        TextEntry::make('currency')
                            ->label('Currency'),
                        TextEntry::make('minimum_order_quantity')
                            ->label('Min. Order Quantity')
                            ->numeric(),
                        TextEntry::make('lead_time_days')
                            ->label('Lead Time')
                            ->suffix(' '.__('days')),
                    ])
                    ->columns(2),

                Section::make(__('Validity'))
                    ->schema([
                        TextEntry::make('valid_from')
                            ->label('Valid From')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('valid_until')
                            ->label('Valid Until')
                            ->date()
                            ->placeholder('-'),
                        IconEntry::make('is_active')
                            ->label('Active')
                            ->boolean(),
                        TextEntry::make('notes')
                            ->label('Notes')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

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
