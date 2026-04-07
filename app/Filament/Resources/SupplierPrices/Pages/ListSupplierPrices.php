<?php

declare(strict_types=1);

namespace App\Filament\Resources\SupplierPrices\Pages;

use App\Filament\Exports\SupplierPriceExporter;
use App\Filament\Imports\SupplierPriceImporter;
use App\Filament\Resources\SupplierPrices\SupplierPriceResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Override;

final class ListSupplierPrices extends ListRecords
{
    protected static string $resource = SupplierPriceResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(SupplierPriceImporter::class)
                ->options(['teamId' => Filament::getTenant()?->getKey()])
                ->label(__('Import'))
                ->color('success'),
            ExportAction::make()
                ->exporter(SupplierPriceExporter::class)
                ->label(__('Export'))
                ->color('warning'),
        ];
    }
}
