<?php

declare(strict_types=1);

namespace App\Filament\Resources\Receipts;

use App\Enums\NavigationGroup;
use App\Filament\Resources\Receipts\Pages\CreateReceipt;
use App\Filament\Resources\Receipts\Pages\EditReceipt;
use App\Filament\Resources\Receipts\Pages\ListReceipts;
use App\Filament\Resources\Receipts\Pages\ViewReceipt;
use App\Filament\Resources\Receipts\Schemas\ReceiptForm;
use App\Filament\Resources\Receipts\Schemas\ReceiptInfolist;
use App\Filament\Resources\Receipts\Tables\ReceiptsTable;
use App\Models\Receipt;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Override;
use UnitEnum;

final class ReceiptResource extends Resource
{
    protected static ?string $model = Receipt::class;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::PURCHASING;

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return ReceiptForm::configure($schema);
    }

    #[Override]
    public static function infolist(Schema $schema): Schema
    {
        return ReceiptInfolist::configure($schema);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return ReceiptsTable::configure($table);
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
            'index' => ListReceipts::route('/'),
            'create' => CreateReceipt::route('/create'),
            'view' => ViewReceipt::route('/{record}'),
            'edit' => EditReceipt::route('/{record}/edit'),
        ];
    }

    #[Override]
    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->with(['receiptLines.product', 'receiptLines.warehouse'])
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
