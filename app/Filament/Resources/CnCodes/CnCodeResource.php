<?php

declare(strict_types=1);

namespace App\Filament\Resources\CnCodes;

use App\Enums\NavigationGroup;
use App\Filament\Resources\CnCodes\Pages\CreateCnCode;
use App\Filament\Resources\CnCodes\Pages\EditCnCode;
use App\Filament\Resources\CnCodes\Pages\ListCnCodes;
use App\Filament\Resources\CnCodes\Schemas\CnCodeForm;
use App\Filament\Resources\CnCodes\Tables\CnCodesTable;
use App\Models\CnCode;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Override;
use UnitEnum;

final class CnCodeResource extends Resource
{
    protected static ?string $model = CnCode::class;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::INTRASTAT;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHashtag;

    protected static ?int $navigationSort = 1;

    protected static bool $isScopedToTenant = false;

    #[Override]
    public static function getModelLabel(): string
    {
        return __('CN Code');
    }

    #[Override]
    public static function getPluralModelLabel(): string
    {
        return __('CN Codes');
    }

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return CnCodeForm::configure($schema);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return CnCodesTable::configure($table);
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
            'index' => ListCnCodes::route('/'),
            'create' => CreateCnCode::route('/create'),
            'edit' => EditCnCode::route('/{record}/edit'),
        ];
    }
}
