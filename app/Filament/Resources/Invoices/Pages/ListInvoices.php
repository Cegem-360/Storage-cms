<?php

declare(strict_types=1);

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Exports\InvoiceExporter;
use App\Filament\Resources\Invoices\InvoiceResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;
use Override;

final class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ExportAction::make()
                ->exporter(InvoiceExporter::class)
                ->label(__('Export'))
                ->color('warning'),
        ];
    }
}
