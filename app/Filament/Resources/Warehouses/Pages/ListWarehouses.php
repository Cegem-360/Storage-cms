<?php

declare(strict_types=1);

namespace App\Filament\Resources\Warehouses\Pages;

use App\Filament\Exports\WarehouseExporter;
use App\Filament\Imports\WarehouseImporter;
use App\Filament\Resources\Warehouses\WarehouseResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Override;

final class ListWarehouses extends ListRecords
{
    protected static string $resource = WarehouseResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(WarehouseImporter::class)
                ->options(['teamId' => Filament::getTenant()?->getKey()])
                ->label(__('Import'))
                ->color('success'),
            ExportAction::make()
                ->exporter(WarehouseExporter::class)
                ->label(__('Export'))
                ->color('warning'),
        ];
    }
}
