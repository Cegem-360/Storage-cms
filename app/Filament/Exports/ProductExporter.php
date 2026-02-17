<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\Product;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

final class ProductExporter extends Exporter
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('sku')
                ->label('SKU'),

            ExportColumn::make('name')
                ->label('Név'),

            ExportColumn::make('barcode')
                ->label('Vonalkód'),

            ExportColumn::make('unit_of_measure')
                ->label('Mértékegység'),

            ExportColumn::make('weight')
                ->label('Súly'),

            ExportColumn::make('category.name')
                ->label('Kategória'),

            ExportColumn::make('supplier.company_name')
                ->label('Beszállító'),

            ExportColumn::make('min_stock')
                ->label('Min. készlet'),

            ExportColumn::make('max_stock')
                ->label('Max. készlet'),

            ExportColumn::make('reorder_point')
                ->label('Újrarendelési pont'),

            ExportColumn::make('price')
                ->label('Ár'),

            ExportColumn::make('status')
                ->label('Státusz'),

            ExportColumn::make('created_at')
                ->label('Létrehozva'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Termékek exportálása befejeződött. '.number_format($export->successful_rows).' sor sikeresen exportálva.';

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= ' '.number_format($failedRowsCount).' sor sikertelen.';
        }

        return $body;
    }
}
