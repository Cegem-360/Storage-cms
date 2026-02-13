<?php

declare(strict_types=1);

namespace App\Filament\Resources\Suppliers\Pages;

use App\Filament\Exports\SupplierExporter;
use App\Filament\Imports\SupplierImporter;
use App\Filament\Resources\Suppliers\SupplierResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use Override;

final class ListSuppliers extends ListRecords
{
    protected static string $resource = SupplierResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(SupplierImporter::class)
                ->label('Importálás')
                ->color('success'),
            ExportAction::make()
                ->exporter(SupplierExporter::class)
                ->label('Exportálás')
                ->color('warning'),
        ];
    }
}
