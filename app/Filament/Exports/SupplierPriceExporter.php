<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\SupplierPrice;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

final class SupplierPriceExporter extends Exporter
{
    protected static ?string $model = SupplierPrice::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('product.name')
                ->label('Termék'),

            ExportColumn::make('product.sku')
                ->label('SKU'),

            ExportColumn::make('supplier.company_name')
                ->label('Beszállító'),

            ExportColumn::make('price')
                ->label('Ár'),

            ExportColumn::make('currency')
                ->label('Pénznem'),

            ExportColumn::make('valid_from')
                ->label('Érvényes ettől'),

            ExportColumn::make('valid_to')
                ->label('Érvényes eddig'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Beszállítói árak exportálása befejeződött. '.number_format($export->successful_rows).' sor sikeresen exportálva.';

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= ' '.number_format($failedRowsCount).' sor sikertelen.';
        }

        return $body;
    }
}
