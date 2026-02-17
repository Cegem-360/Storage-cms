<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\Warehouse;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

final class WarehouseExporter extends Exporter
{
    protected static ?string $model = Warehouse::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('code')
                ->label('Kód'),

            ExportColumn::make('name')
                ->label('Név'),

            ExportColumn::make('address')
                ->label('Cím'),

            ExportColumn::make('type')
                ->label('Típus'),

            ExportColumn::make('capacity')
                ->label('Kapacitás'),

            ExportColumn::make('is_active')
                ->label('Aktív'),

            ExportColumn::make('is_consignment')
                ->label('Konszignációs'),

            ExportColumn::make('valuation_method')
                ->label('Értékelési módszer'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Raktárak exportálása befejeződött. '.number_format($export->successful_rows).' sor sikeresen exportálva.';

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= ' '.number_format($failedRowsCount).' sor sikertelen.';
        }

        return $body;
    }
}
