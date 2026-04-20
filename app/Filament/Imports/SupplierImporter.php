<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Filament\Imports\Columns\ImportColumn;
use App\Filament\Imports\Concerns\LocalizedNotifications;
use App\Models\Supplier;
use Filament\Actions\Imports\Importer;
use Filament\Forms\Components\Checkbox;

final class SupplierImporter extends Importer
{
    use LocalizedNotifications;

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
                ->localizedBoolean(default: true),
        ];
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Checkbox::make('updateExisting')
                ->label(__('Update existing records')),
        ];
    }

    public function resolveRecord(): Supplier
    {
        if ($this->options['updateExisting'] ?? false) {
            return Supplier::query()->firstOrNew([
                'code' => $this->data['code'],
            ]);
        }

        return new Supplier();
    }

    protected function beforeCreate(): void
    {
        $this->record->team_id = $this->options['teamId'] ?? null;

        if ($this->record->is_active === null) {
            $this->record->is_active = true;
        }
    }
}
