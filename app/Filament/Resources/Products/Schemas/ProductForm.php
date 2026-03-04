<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\ProductStatus;
use App\Enums\UnitType;
use App\Services\BarcodeService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

final class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->tabs([
                        Tab::make(__('Basic Information'))
                            ->schema([
                                TextInput::make('sku')
                                    ->label(__('SKU'))
                                    ->scopedUnique(ignoreRecord: true)
                                    ->maxLength(100),
                                TextInput::make('name')
                                    ->label(__('Product Name'))
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('barcode')
                                    ->label(__('Barcode'))
                                    ->maxLength(100)
                                    ->afterContent(
                                        Action::make('generateBarcode')
                                            ->label(__('Generate Barcode'))
                                            ->icon(Heroicon::OutlinedQrCode)
                                            ->color('gray')
                                            ->action(function (Set $set): void {
                                                $set('barcode', BarcodeService::generateEan13());

                                                Notification::make()
                                                    ->title(__('Barcode generated successfully'))
                                                    ->success()
                                                    ->send();
                                            }),
                                    ),
                                Select::make('category_id')
                                    ->label(__('Category'))
                                    ->relationship('category', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required(),
                                    ]),
                                Select::make('supplier_id')
                                    ->label(__('Primary Supplier'))
                                    ->relationship('supplier', 'company_name')
                                    ->searchable()
                                    ->preload(),
                                Select::make('status')
                                    ->label(__('Status'))
                                    ->options(ProductStatus::class)
                                    ->default(ProductStatus::ACTIVE)
                                    ->required(),
                                Textarea::make('description')
                                    ->label(__('Description'))
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ])
                            ->columns(3),

                        Tab::make(__('Measurements & Pricing'))
                            ->schema([
                                Select::make('unit_of_measure')
                                    ->label(__('Unit of Measure'))
                                    ->options(UnitType::class)
                                    ->default(UnitType::PIECE)
                                    ->required(),
                                TextInput::make('weight')
                                    ->label(__('Weight (kg)'))
                                    ->numeric()
                                    ->minValue(0)
                                    ->suffix('kg'),
                                TextInput::make('dimensions.length')
                                    ->label(__('Length (cm)'))
                                    ->numeric()
                                    ->minValue(0)
                                    ->suffix('cm'),
                                TextInput::make('dimensions.width')
                                    ->label(__('Width (cm)'))
                                    ->numeric()
                                    ->minValue(0)
                                    ->suffix('cm'),
                                TextInput::make('dimensions.height')
                                    ->label(__('Height (cm)'))
                                    ->numeric()
                                    ->minValue(0)
                                    ->suffix('cm'),
                                TextInput::make('price')
                                    ->label(__('Price'))
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->prefix('Ft')
                                    ->suffix(__('/ unit')),
                            ])
                            ->columns(3),

                        Tab::make(__('Stock Management'))
                            ->schema([
                                TextInput::make('min_stock')
                                    ->label(__('Minimum Stock'))
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->helperText(__('Alert when stock falls below this level')),
                                TextInput::make('reorder_point')
                                    ->label(__('Reorder Point'))
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->helperText(__('Trigger reorder when reaching this level')),
                                TextInput::make('max_stock')
                                    ->label(__('Maximum Stock'))
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->helperText(__('Maximum stock level to maintain')),
                            ])
                            ->columns(3),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
