<?php

declare(strict_types=1);

namespace App\Filament\Resources\Stocks\Pages;

use App\Filament\Exports\StockExporter;
use App\Filament\Resources\Stocks\StockResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;
use Override;

final class ListStocks extends ListRecords
{
    protected static string $resource = StockResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ExportAction::make()
                ->exporter(StockExporter::class)
                ->label('Exportálás')
                ->color('warning'),
        ];
    }
}
