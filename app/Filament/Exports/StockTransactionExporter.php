<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\StockTransaction;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

final class StockTransactionExporter extends Exporter
{
    protected static ?string $model = StockTransaction::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('product.name')
                ->label('Termék'),

            ExportColumn::make('warehouse.name')
                ->label('Raktár'),

            ExportColumn::make('type')
                ->label('Típus'),

            ExportColumn::make('quantity')
                ->label('Mennyiség'),

            ExportColumn::make('created_at')
                ->label('Dátum'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Készletmozgások exportálása befejeződött. '.number_format($export->successful_rows).' sor sikeresen exportálva.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' sor sikertelen.';
        }

        return $body;
    }
}
