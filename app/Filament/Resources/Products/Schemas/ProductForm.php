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
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

final class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('sku')
                            ->label('SKU')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(100),
                        TextInput::make('name')
                            ->label('Product Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('barcode')
                            ->label('Barcode')
                            ->maxLength(100)
                            ->afterContent(
                                Action::make('generateBarcode')
                                    ->label('Generate Barcode')
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
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Classification')
                    ->schema([
                        Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required(),
                            ]),
                        Select::make('supplier_id')
                            ->label('Primary Supplier')
                            ->relationship('supplier', 'company_name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('status')
                            ->label('Status')
                            ->options(ProductStatus::class)
                            ->default(ProductStatus::ACTIVE)
                            ->required(),
                    ])
                    ->columns(3),

                Section::make('Measurements')
                    ->schema([
                        Select::make('unit_of_measure')
                            ->label('Unit of Measure')
                            ->options(UnitType::class)
                            ->default(UnitType::PIECE)
                            ->required(),
                        TextInput::make('weight')
                            ->label('Weight (kg)')
                            ->numeric()
                            ->minValue(0)
                            ->suffix('kg'),
                        TextInput::make('dimensions.length')
                            ->label('Length (cm)')
                            ->numeric()
                            ->minValue(0)
                            ->suffix('cm'),
                        TextInput::make('dimensions.width')
                            ->label('Width (cm)')
                            ->numeric()
                            ->minValue(0)
                            ->suffix('cm'),
                        TextInput::make('dimensions.height')
                            ->label('Height (cm)')
                            ->numeric()
                            ->minValue(0)
                            ->suffix('cm'),
                    ])
                    ->columns(3)
                    ->collapsible(),

                Section::make('Pricing')
                    ->schema([
                        TextInput::make('price')
                            ->label('Price')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->prefix('Ft')
                            ->suffix('/ unit'),
                    ])
                    ->columns(1),

                Section::make('Stock Management')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('min_stock')
                                    ->label('Minimum Stock')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->helperText(__('Alert when stock falls below this level')),
                                TextInput::make('reorder_point')
                                    ->label('Reorder Point')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->helperText(__('Trigger reorder when reaching this level')),
                                TextInput::make('max_stock')
                                    ->label('Maximum Stock')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->helperText(__('Maximum stock level to maintain')),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }
}
