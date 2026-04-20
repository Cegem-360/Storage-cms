<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Filament\Imports\Columns\ImportColumn;
use App\Filament\Imports\Concerns\LocalizedNotifications;
use App\Models\SupplierPrice;
use Filament\Actions\Imports\Importer;
use Filament\Forms\Components\Checkbox;

final class SupplierPriceImporter extends Importer
{
    use LocalizedNotifications;

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
                ->localizedBoolean(default: true),
            ImportColumn::make('notes'),
        ];
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Checkbox::make('updateExisting')
                ->label(__('Update existing records')),
        ];
    }

    public function resolveRecord(): SupplierPrice
    {
        if ($this->options['updateExisting'] ?? false) {
            return SupplierPrice::query()->firstOrNew([
                'product_id' => $this->data['product_id'] ?? null,
                'supplier_id' => $this->data['supplier_id'] ?? null,
            ]);
        }

        return new SupplierPrice();
    }

    protected function beforeCreate(): void
    {
        $this->record->team_id = $this->options['teamId'] ?? null;

        if ($this->record->is_active === null) {
            $this->record->is_active = true;
        }
    }
}
