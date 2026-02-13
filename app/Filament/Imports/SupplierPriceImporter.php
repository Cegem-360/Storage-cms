<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Models\SupplierPrice;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

final class SupplierPriceImporter extends Importer
{
    protected static ?string $model = SupplierPrice::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('supplier')
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('product')
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('price')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'numeric', 'min:0']),
            ImportColumn::make('currency')
                ->rules(['max:3']),
            ImportColumn::make('minimum_order_quantity')
                ->numeric()
                ->rules(['nullable', 'integer', 'min:1']),
            ImportColumn::make('lead_time_days')
                ->numeric()
                ->rules(['nullable', 'integer', 'min:0']),
            ImportColumn::make('valid_from')
                ->rules(['nullable', 'date']),
            ImportColumn::make('valid_until')
                ->rules(['nullable', 'date']),
            ImportColumn::make('is_active')
                ->boolean(),
            ImportColumn::make('notes'),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __('Your supplier price import has completed and :count row(s) imported.', [
            'count' => Number::format($import->successful_rows),
        ]);

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.__(':count row(s) failed to import.', [
                'count' => Number::format($failedRowsCount),
            ]);
        }

        return $body;
    }

    public function resolveRecord(): SupplierPrice
    {
        return SupplierPrice::firstOrNew([
            'product_id' => $this->data['product_id'] ?? null,
            'supplier_id' => $this->data['supplier_id'] ?? null,
        ]);
    }
}
