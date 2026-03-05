<?php

declare(strict_types=1);

namespace App\Filament\Resources\IntrastatDeclarations;

use App\Enums\NavigationGroup;
use App\Filament\Resources\IntrastatDeclarations\Pages\CreateIntrastatDeclaration;
use App\Filament\Resources\IntrastatDeclarations\Pages\EditIntrastatDeclaration;
use App\Filament\Resources\IntrastatDeclarations\Pages\ListIntrastatDeclarations;
use App\Filament\Resources\IntrastatDeclarations\RelationManagers\IntrastatLinesRelationManager;
use App\Filament\Resources\IntrastatDeclarations\Schemas\IntrastatDeclarationForm;
use App\Filament\Resources\IntrastatDeclarations\Tables\IntrastatDeclarationsTable;
use App\Models\IntrastatDeclaration;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Override;
use UnitEnum;

final class IntrastatDeclarationResource extends Resource
{
    protected static ?string $model = IntrastatDeclaration::class;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::INTRASTAT;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocument;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Declarations';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return IntrastatDeclarationForm::configure($schema);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return IntrastatDeclarationsTable::configure($table);
    }

    #[Override]
    public static function getRelations(): array
    {
        return [
            IntrastatLinesRelationManager::class,
        ];
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListIntrastatDeclarations::route('/'),
            'create' => CreateIntrastatDeclaration::route('/create'),
            'edit' => EditIntrastatDeclaration::route('/{record}/edit'),
        ];
    }
}
