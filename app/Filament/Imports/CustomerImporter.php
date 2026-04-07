<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Filament\Imports\Columns\ImportColumn;
use App\Models\Customer;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Override;

final class CustomerImporter extends Importer
{
    protected static ?string $model = Customer::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('first_name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('last_name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),
            ImportColumn::make('phone_number')
                ->rules(['max:255']),
            ImportColumn::make('address'),
            ImportColumn::make('city')
                ->rules(['max:255']),
            ImportColumn::make('state')
                ->rules(['max:255']),
            ImportColumn::make('postal_code')
                ->rules(['max:255']),
            ImportColumn::make('country')
                ->rules(['max:255']),
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
    public function resolveRecord(): Customer
    {
        return Customer::query()->firstOrNew([
            'email' => $this->data['email'],
        ]);
    }

    protected function beforeCreate(): void
    {
        $this->record->team_id = $this->options['teamId'] ?? null;
    }
}
