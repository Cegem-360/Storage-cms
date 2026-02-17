<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\IntrastatDeclaration;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

final class IntrastatDeclarationExporter extends Exporter
{
    protected static ?string $model = IntrastatDeclaration::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('declaration_number')
                ->label('Nyilatkozat szám'),

            ExportColumn::make('direction')
                ->label('Irány'),

            ExportColumn::make('reference_year')
                ->label('Referencia év'),

            ExportColumn::make('reference_month')
                ->label('Referencia hónap'),

            ExportColumn::make('status')
                ->label('Státusz'),

            ExportColumn::make('total_invoice_value')
                ->label('Számla érték összesen'),

            ExportColumn::make('total_statistical_value')
                ->label('Statisztikai érték összesen'),

            ExportColumn::make('total_net_mass')
                ->label('Nettó tömeg összesen'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Intrastat nyilatkozatok exportálása befejeződött. '.number_format($export->successful_rows).' sor sikeresen exportálva.';

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= ' '.number_format($failedRowsCount).' sor sikertelen.';
        }

        return $body;
    }
}
