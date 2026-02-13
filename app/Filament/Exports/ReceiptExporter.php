<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\Receipt;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

final class ReceiptExporter extends Exporter
{
    protected static ?string $model = Receipt::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('order.order_number')
                ->label('Rendelés'),

            ExportColumn::make('warehouse.name')
                ->label('Raktár'),

            ExportColumn::make('status')
                ->label('Státusz'),

            ExportColumn::make('received_at')
                ->label('Átvétel dátuma'),

            ExportColumn::make('created_at')
                ->label('Létrehozva'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Átvételek exportálása befejeződött. '.number_format($export->successful_rows).' sor sikeresen exportálva.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' sor sikertelen.';
        }

        return $body;
    }
}
