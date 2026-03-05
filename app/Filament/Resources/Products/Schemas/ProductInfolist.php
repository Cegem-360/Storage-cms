<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\ProductStatus;
use App\Models\Product;
use App\Models\Team;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Collection;

final class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('basic_information')
                    ->schema([
                        TextEntry::make('sku')
                            ->label(__('SKU')),
                        TextEntry::make('name')
                            ->label(__('Product Name')),
                        TextEntry::make('barcode')
                            ->placeholder('-'),
                        TextEntry::make('description')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('classification')
                    ->schema([
                        TextEntry::make('category.name')
                            ->label(__('Category')),
                        TextEntry::make('supplier.company_name')
                            ->label(__('Primary Supplier')),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (ProductStatus $state): string => match ($state) {
                                ProductStatus::ACTIVE => 'success',
                                ProductStatus::INACTIVE => 'gray',
                                ProductStatus::DISCONTINUED => 'danger',
                                ProductStatus::OUT_OF_STOCK => 'warning',
                                default => 'gray',
                            }),
                    ])
                    ->columns(3),

                Section::make('measurements')
                    ->schema([
                        TextEntry::make('unit_of_measure'),
                        TextEntry::make('weight')
                            ->numeric()
                            ->suffix(' kg')
                            ->placeholder('-'),
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('dimensions.length')
                                    ->label(__('Length'))
                                    ->suffix(' cm')
                                    ->placeholder('-'),
                                TextEntry::make('dimensions.width')
                                    ->label(__('Width'))
                                    ->suffix(' cm')
                                    ->placeholder('-'),
                                TextEntry::make('dimensions.height')
                                    ->label(__('Height'))
                                    ->suffix(' cm')
                                    ->placeholder('-'),
                            ]),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('pricing')
                    ->schema([
                        TextEntry::make('price')
                            ->money(Team::currency())
                            ->suffix(' / unit'),
                    ])
                    ->columns(1),

                Section::make('stock_management')
                    ->schema([
                        TextEntry::make('min_stock')
                            ->numeric(),
                        TextEntry::make('reorder_point')
                            ->numeric(),
                        TextEntry::make('max_stock')
                            ->numeric(),
                    ])
                    ->columns(3)
                    ->collapsible(),

                Section::make('stock_by_warehouse')
                    ->schema([
                        RepeatableEntry::make('stocks')
                            ->label('')
                            ->schema([
                                TextEntry::make('warehouse.name')
                                    ->label(__('Warehouse')),
                                TextEntry::make('quantity')
                                    ->label(__('Available Quantity'))
                                    ->numeric()
                                    ->badge()
                                    ->color(fn ($state, $record): string => match (true) {
                                        $state === 0 => 'gray',
                                        $record->isLowStock() => 'danger',
                                        default => 'success',
                                    }),
                                TextEntry::make('reserved_quantity')
                                    ->label(__('Reserved'))
                                    ->numeric()
                                    ->badge()
                                    ->color('warning'),
                                TextEntry::make('Available')
                                    ->label(__('Available'))
                                    ->state(fn ($record): int => $record->getAvailableQuantity())
                                    ->numeric()
                                    ->badge()
                                    ->color('info'),
                                TextEntry::make('status')
                                    ->badge(),
                            ])
                            ->columns(5)
                            ->columnSpanFull()
                            ->contained(false),
                        TextEntry::make('Total Stock')
                            ->label(__('Total Stock Across All Warehouses'))
                            ->state(fn (Product $record): int => $record->getTotalStock())
                            ->numeric()
                            ->size('lg')
                            ->weight('bold')
                            ->badge()
                            ->color('primary'),
                    ])
                    ->collapsible(),

                Section::make('expected_arrivals')
                    ->schema([
                        RepeatableEntry::make('expected_arrivals')
                            ->label('')
                            ->state(fn (Product $record): Collection => $record->getExpectedArrivals())
                            ->schema([
                                TextEntry::make('order_number')
                                    ->label(__('Order #')),
                                TextEntry::make('supplier.name')
                                    ->label(__('Supplier')),
                                TextEntry::make('delivery_date')
                                    ->label(__('Expected Date'))
                                    ->date()
                                    ->badge()
                                    ->color(function ($record): string {
                                        $daysUntil = now()->diffInDays($record->delivery_date, false);

                                        return match (true) {
                                            $daysUntil < 0 => 'danger',
                                            $daysUntil <= 3 => 'warning',
                                            default => 'success',
                                        };
                                    }),
                                TextEntry::make('quantity')
                                    ->state(fn ($record, Product $rootRecord): int => $record->orderLines
                                        ->where('product_id', $rootRecord->id)
                                        ->sum('quantity'))
                                    ->numeric()
                                    ->badge()
                                    ->color('info'),
                                TextEntry::make('status')
                                    ->badge(),
                            ])
                            ->columns(5)
                            ->columnSpanFull()
                            ->contained(false)
                            ->visible(fn (Product $record): bool => $record->getExpectedArrivals()->isNotEmpty()),
                        TextEntry::make('Total Expected')
                            ->label(__('Total Expected Quantity'))
                            ->state(fn (Product $record): int => $record->getTotalExpectedQuantity())
                            ->numeric()
                            ->size('lg')
                            ->weight('bold')
                            ->badge()
                            ->color('warning')
                            ->visible(fn (Product $record): bool => $record->getExpectedArrivals()->isNotEmpty()),
                    ])
                    ->collapsible()
                    ->visible(fn (Product $record): bool => $record->getExpectedArrivals()->isNotEmpty()),

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
                            ->visible(fn (Product $record): bool => $record->trashed()),
                    ])
                    ->columns(3)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
