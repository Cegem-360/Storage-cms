<?php

declare(strict_types=1);

namespace App\Filament\Resources\SupplierPrices;

use App\Enums\NavigationGroup;
use App\Filament\Resources\SupplierPrices\Pages\CreateSupplierPrice;
use App\Filament\Resources\SupplierPrices\Pages\EditSupplierPrice;
use App\Filament\Resources\SupplierPrices\Pages\ListSupplierPrices;
use App\Filament\Resources\SupplierPrices\Pages\ViewSupplierPrice;
use App\Filament\Resources\SupplierPrices\Schemas\SupplierPriceForm;
use App\Filament\Resources\SupplierPrices\Schemas\SupplierPriceInfolist;
use App\Filament\Resources\SupplierPrices\Tables\SupplierPricesTable;
use App\Models\SupplierPrice;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Override;
use UnitEnum;

final class SupplierPriceResource extends Resource
{
    protected static ?string $model = SupplierPrice::class;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::SALES;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static ?int $navigationSort = 5;

    #[Override]
    public static function getModelLabel(): string
    {
        return __('Supplier Price');
    }

    #[Override]
    public static function getPluralModelLabel(): string
    {
        return __('Supplier Prices');
    }

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return SupplierPriceForm::configure($schema);
    }

    #[Override]
    public static function infolist(Schema $schema): Schema
    {
        return SupplierPriceInfolist::configure($schema);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return SupplierPricesTable::configure($table);
    }

    #[Override]
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListSupplierPrices::route('/'),
            'create' => CreateSupplierPrice::route('/create'),
            'view' => ViewSupplierPrice::route('/{record}'),
            'edit' => EditSupplierPrice::route('/{record}/edit'),
        ];
    }
}
