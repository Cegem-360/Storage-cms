<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\Employee;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

final class EmployeeExporter extends Exporter
{
    protected static ?string $model = Employee::class;

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

            ExportColumn::make('phone')
                ->label('Telefon'),

            ExportColumn::make('position')
                ->label('Pozíció'),

            ExportColumn::make('warehouse.name')
                ->label('Raktár'),

            ExportColumn::make('created_at')
                ->label('Létrehozva'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Dolgozók exportálása befejeződött. '.number_format($export->successful_rows).' sor sikeresen exportálva.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' sor sikertelen.';
        }

        return $body;
    }
}
