<?php

declare(strict_types=1);

namespace App\Filament\Resources\Inventories;

use App\Enums\NavigationGroup;
use App\Filament\Resources\Inventories\Pages\CreateInventory;
use App\Filament\Resources\Inventories\Pages\EditInventory;
use App\Filament\Resources\Inventories\Pages\ListInventories;
use App\Filament\Resources\Inventories\RelationManagers\InventoryLinesRelationManager;
use App\Filament\Resources\Inventories\Schemas\InventoryForm;
use App\Filament\Resources\Inventories\Tables\InventoriesTable;
use App\Models\Inventory;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Override;
use UnitEnum;

final class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::INVENTORY;

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return InventoryForm::configure($schema);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return InventoriesTable::configure($table);
    }

    #[Override]
    public static function getRelations(): array
    {
        return [
            InventoryLinesRelationManager::class,
        ];
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListInventories::route('/'),
            'create' => CreateInventory::route('/create'),
            'edit' => EditInventory::route('/{record}/edit'),
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
