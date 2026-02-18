<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\Invoice;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

final class InvoiceExporter extends Exporter
{
    protected static ?string $model = Invoice::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('invoice_number')
                ->label(__('Invoice Number')),
            ExportColumn::make('order.order_number')
                ->label(__('Order')),
            ExportColumn::make('status')
                ->label(__('Status')),
            ExportColumn::make('invoice_date')
                ->label(__('Invoice Date')),
            ExportColumn::make('due_date')
                ->label(__('Due Date')),
            ExportColumn::make('total_amount')
                ->label(__('Total')),
            ExportColumn::make('currency')
                ->label(__('Currency')),
            ExportColumn::make('created_at')
                ->label(__('Created At')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = __('Your invoice export has completed and :count row(s) exported.', [
            'count' => Number::format($export->successful_rows),
        ]);

        if (($failedRowsCount = $export->getFailedRowsCount()) !== 0) {
            $body .= ' '.__(':count row(s) failed to export.', [
                'count' => Number::format($failedRowsCount),
            ]);
        }

        return $body;
    }
}
