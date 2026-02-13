<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Models\Supplier;
use Filament\Actions\Imports\ImportColumn;
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
                ->boolean(),
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
                ->boolean()
                ->rules(['required', 'boolean']),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Beszállítók importálása befejeződött. '.Number::format($import->successful_rows).' '.str('sor')->plural($import->successful_rows).' importálva.';

        if (($failedRowsCount = $import->getFailedRowsCount()) !== 0) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('sor')->plural($failedRowsCount).' importálása sikertelen.';
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
}
