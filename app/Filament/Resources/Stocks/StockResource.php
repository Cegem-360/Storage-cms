<?php

declare(strict_types=1);

namespace App\Filament\Resources\Stocks;

use App\Enums\NavigationGroup;
use App\Filament\Resources\Stocks\Pages\CreateStock;
use App\Filament\Resources\Stocks\Pages\EditStock;
use App\Filament\Resources\Stocks\Pages\ListStocks;
use App\Filament\Resources\Stocks\Pages\ViewStock;
use App\Filament\Resources\Stocks\Schemas\StockForm;
use App\Filament\Resources\Stocks\Schemas\StockInfolist;
use App\Filament\Resources\Stocks\Tables\StocksTable;
use App\Models\Stock;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Override;
use UnitEnum;

final class StockResource extends Resource
{
    protected static ?string $model = Stock::class;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::INVENTORY;

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return StockForm::configure($schema);
    }

    #[Override]
    public static function infolist(Schema $schema): Schema
    {
        return StockInfolist::configure($schema);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return StocksTable::configure($table);
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
            'index' => ListStocks::route('/'),
            'create' => CreateStock::route('/create'),
            'view' => ViewStock::route('/{record}'),
            'edit' => EditStock::route('/{record}/edit'),
        ];
    }

    #[Override]
    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
