<?php

declare(strict_types=1);

namespace App\Filament\Resources\Batches\Pages;

use App\Filament\Exports\BatchExporter;
use App\Filament\Imports\BatchImporter;
use App\Filament\Resources\Batches\BatchResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Override;

final class ListBatches extends ListRecords
{
    protected static string $resource = BatchResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(BatchImporter::class)
                ->options(['teamId' => Filament::getTenant()?->getKey()])
                ->label(__('Import'))
                ->color('success'),
            ExportAction::make()
                ->exporter(BatchExporter::class)
                ->label(__('Export'))
                ->color('warning'),
        ];
    }
}
