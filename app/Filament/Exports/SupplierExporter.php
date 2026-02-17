<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\Supplier;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

final class SupplierExporter extends Exporter
{
    protected static ?string $model = Supplier::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('code')
                ->label('Kód'),

            ExportColumn::make('company_name')
                ->label('Cégnév'),

            ExportColumn::make('trade_name')
                ->label('Kereskedelmi név'),

            ExportColumn::make('country_code')
                ->label('Országkód'),

            ExportColumn::make('is_eu_member')
                ->label('EU tag'),

            ExportColumn::make('tax_number')
                ->label('Adószám'),

            ExportColumn::make('eu_tax_number')
                ->label('EU adószám'),

            ExportColumn::make('contact_person')
                ->label('Kapcsolattartó'),

            ExportColumn::make('email')
                ->label('Email'),

            ExportColumn::make('phone')
                ->label('Telefon'),

            ExportColumn::make('is_active')
                ->label('Aktív'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Beszállítók exportálása befejeződött. '.number_format($export->successful_rows).' sor sikeresen exportálva.';

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= ' '.number_format($failedRowsCount).' sor sikertelen.';
        }

        return $body;
    }
}
