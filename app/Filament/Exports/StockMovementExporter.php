<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\StockMovement;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

final class StockMovementExporter extends Exporter
{
    protected static ?string $model = StockMovement::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('product.name')
                ->label('Termék'),

            ExportColumn::make('sourceWarehouse.name')
                ->label('Forrás raktár'),

            ExportColumn::make('targetWarehouse.name')
                ->label('Cél raktár'),

            ExportColumn::make('quantity')
                ->label('Mennyiség'),

            ExportColumn::make('status')
                ->label('Státusz'),

            ExportColumn::make('created_at')
                ->label('Létrehozva'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Raktárközi mozgások exportálása befejeződött. '.number_format($export->successful_rows).' sor sikeresen exportálva.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' sor sikertelen.';
        }

        return $body;
    }
}
