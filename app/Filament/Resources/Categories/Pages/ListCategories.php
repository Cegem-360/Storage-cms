<?php

declare(strict_types=1);

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Exports\CategoryExporter;
use App\Filament\Imports\CategoryImporter;
use App\Filament\Resources\Categories\CategoryResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Override;

final class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(CategoryImporter::class)
                ->options(['teamId' => Filament::getTenant()?->getKey()])
                ->label(__('Import'))
                ->color('success'),
            ExportAction::make()
                ->exporter(CategoryExporter::class)
                ->label(__('Export'))
                ->color('warning'),
        ];
    }
}
