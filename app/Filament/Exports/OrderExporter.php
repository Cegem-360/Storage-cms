<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\Order;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

final class OrderExporter extends Exporter
{
    protected static ?string $model = Order::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('order_number')
                ->label('Rendelésszám'),

            ExportColumn::make('type')
                ->label('Típus'),

            ExportColumn::make('status')
                ->label('Státusz'),

            ExportColumn::make('customer.last_name')
                ->label('Ügyfél'),

            ExportColumn::make('supplier.company_name')
                ->label('Beszállító'),

            ExportColumn::make('total_amount')
                ->label('Összeg'),

            ExportColumn::make('order_date')
                ->label('Rendelés dátuma'),

            ExportColumn::make('created_at')
                ->label('Létrehozva'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Rendelések exportálása befejeződött. '.number_format($export->successful_rows).' sor sikeresen exportálva.';

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= ' '.number_format($failedRowsCount).' sor sikertelen.';
        }

        return $body;
    }
}
