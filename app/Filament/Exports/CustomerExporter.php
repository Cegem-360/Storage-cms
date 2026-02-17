<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\Customer;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

final class CustomerExporter extends Exporter
{
    protected static ?string $model = Customer::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('first_name')
                ->label('Vezetéknév'),

            ExportColumn::make('last_name')
                ->label('Keresztnév'),

            ExportColumn::make('email')
                ->label('Email'),

            ExportColumn::make('phone_number')
                ->label('Telefonszám'),

            ExportColumn::make('address')
                ->label('Cím'),

            ExportColumn::make('city')
                ->label('Város'),

            ExportColumn::make('state')
                ->label('Megye'),

            ExportColumn::make('postal_code')
                ->label('Irányítószám'),

            ExportColumn::make('country')
                ->label('Ország'),

            ExportColumn::make('is_active')
                ->label('Aktív'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Ügyfelek exportálása befejeződött. '.number_format($export->successful_rows).' sor sikeresen exportálva.';

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= ' '.number_format($failedRowsCount).' sor sikertelen.';
        }

        return $body;
    }
}
