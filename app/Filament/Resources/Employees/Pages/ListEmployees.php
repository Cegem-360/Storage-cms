<?php

declare(strict_types=1);

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Exports\EmployeeExporter;
use App\Filament\Imports\EmployeeImporter;
use App\Filament\Resources\Employees\EmployeeResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use Override;

final class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(EmployeeImporter::class)
                ->label('Importálás')
                ->color('success'),
            ExportAction::make()
                ->exporter(EmployeeExporter::class)
                ->label('Exportálás')
                ->color('warning'),
        ];
    }
}
