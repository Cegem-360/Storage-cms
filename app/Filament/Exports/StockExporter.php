<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\Stock;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

final class StockExporter extends Exporter
{
    protected static ?string $model = Stock::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('product.name')
                ->label('Termék'),

            ExportColumn::make('product.sku')
                ->label('SKU'),

            ExportColumn::make('warehouse.name')
                ->label('Raktár'),

            ExportColumn::make('batch.batch_number')
                ->label('Tétel'),

            ExportColumn::make('quantity')
                ->label('Mennyiség'),

            ExportColumn::make('reserved_quantity')
                ->label('Foglalt mennyiség'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Készlet exportálása befejeződött. '.number_format($export->successful_rows).' sor sikeresen exportálva.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' sor sikertelen.';
        }

        return $body;
    }
}
