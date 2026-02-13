<?php

declare(strict_types=1);

namespace App\Filament\Resources\IntrastatDeclarations\Pages;

use App\Filament\Exports\IntrastatDeclarationExporter;
use App\Filament\Resources\IntrastatDeclarations\IntrastatDeclarationResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;
use Override;

final class ListIntrastatDeclarations extends ListRecords
{
    protected static string $resource = IntrastatDeclarationResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ExportAction::make()
                ->exporter(IntrastatDeclarationExporter::class)
                ->label('Exportálás')
                ->color('warning'),
        ];
    }
}
