<?php

declare(strict_types=1);

namespace App\Filament\Resources\Inventories\Pages;

use App\Filament\Exports\InventoryExporter;
use App\Filament\Resources\Inventories\InventoryResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;
use Override;

final class ListInventories extends ListRecords
{
    protected static string $resource = InventoryResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ExportAction::make()
                ->exporter(InventoryExporter::class)
                ->label('Exportálás')
                ->color('warning'),
        ];
    }
}
