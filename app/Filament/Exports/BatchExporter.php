<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\Batch;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

final class BatchExporter extends Exporter
{
    protected static ?string $model = Batch::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('batch_number')
                ->label('Tételszám'),

            ExportColumn::make('product.name')
                ->label('Termék'),

            ExportColumn::make('supplier.company_name')
                ->label('Beszállító'),

            ExportColumn::make('manufacture_date')
                ->label('Gyártási dátum'),

            ExportColumn::make('expiry_date')
                ->label('Lejárati dátum'),

            ExportColumn::make('quantity')
                ->label('Mennyiség'),

            ExportColumn::make('quality_status')
                ->label('Minőségi állapot'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Tételek exportálása befejeződött. '.number_format($export->successful_rows).' sor sikeresen exportálva.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' sor sikertelen.';
        }

        return $body;
    }
}
