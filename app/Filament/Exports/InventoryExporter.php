<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\Inventory;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

final class InventoryExporter extends Exporter
{
    protected static ?string $model = Inventory::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('warehouse.name')
                ->label('Raktár'),

            ExportColumn::make('status')
                ->label('Státusz'),

            ExportColumn::make('scheduled_at')
                ->label('Ütemezve'),

            ExportColumn::make('completed_at')
                ->label('Befejezve'),

            ExportColumn::make('created_at')
                ->label('Létrehozva'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Leltárak exportálása befejeződött. '.number_format($export->successful_rows).' sor sikeresen exportálva.';

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= ' '.number_format($failedRowsCount).' sor sikertelen.';
        }

        return $body;
    }
}
