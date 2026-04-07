<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Filament\Imports\Columns\ImportColumn;
use App\Models\Supplier;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Override;

final class SupplierImporter extends Importer
{
    protected static ?string $model = Supplier::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('code')
                ->requiredMapping()
                ->rules(['required', 'max:50']),
            ImportColumn::make('company_name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('trade_name')
                ->rules(['max:255']),
            ImportColumn::make('country_code')
                ->requiredMapping()
                ->rules(['required', 'max:2']),
            ImportColumn::make('is_eu_member')
                ->localizedBoolean(),
            ImportColumn::make('tax_number')
                ->rules(['max:50']),
            ImportColumn::make('eu_tax_number')
                ->rules(['max:50']),
            ImportColumn::make('contact_person')
                ->rules(['max:255']),
            ImportColumn::make('email')
                ->rules(['email', 'max:255']),
            ImportColumn::make('phone')
                ->rules(['max:50']),
            ImportColumn::make('is_active')
                ->requiredMapping()
                ->localizedBoolean(default: true)
                ->rules(['required', 'boolean']),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __('Import completed, :count row(s) imported.', [
            'count' => Number::format($import->successful_rows),
        ]);

        if (($failedRowsCount = $import->getFailedRowsCount()) !== 0) {
            $body .= ' '.__(':count row(s) failed to import.', [
                'count' => Number::format($failedRowsCount),
            ]);
        }

        return $body;
    }

    #[Override]
    public function resolveRecord(): Supplier
    {
        return Supplier::query()->firstOrNew([
            'code' => $this->data['code'],
        ]);
    }

    protected function beforeCreate(): void
    {
        $this->record->team_id = $this->options['teamId'] ?? null;
    }
}
