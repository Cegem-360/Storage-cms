<?php

declare(strict_types=1);

namespace App\Filament\Resources\SupplierPrices\Pages;

use App\Filament\Imports\SupplierPriceImporter;
use App\Filament\Resources\SupplierPrices\SupplierPriceResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

final class ListSupplierPrices extends ListRecords
{
    protected static string $resource = SupplierPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(SupplierPriceImporter::class)
                ->label('Import Price List'),
        ];
    }
}
