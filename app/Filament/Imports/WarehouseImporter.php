<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Filament\Imports\Columns\ImportColumn;
use App\Filament\Imports\Concerns\LocalizedNotifications;
use App\Filament\Imports\Concerns\NormalizesNumericState;
use App\Models\Warehouse;
use Filament\Actions\Imports\Importer;
use Filament\Forms\Components\Checkbox;

final class WarehouseImporter extends Importer
{
    use LocalizedNotifications;
    use NormalizesNumericState;

    protected static ?string $model = Warehouse::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('code')
                ->requiredMapping()
                ->rules(['required', 'max:50']),
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('address'),
            ImportColumn::make('type')
                ->requiredMapping()
                ->rules(['required', 'max:50']),
            ImportColumn::make('capacity')
                ->numeric()
                ->castStateUsing(fn (int|float|string|null $state): ?int => self::normalizeInt($state))
                ->rules(['nullable', 'integer']),
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

    public function resolveRecord(): Warehouse
    {
        if ($this->options['updateExisting'] ?? false) {
            return Warehouse::query()->firstOrNew([
                'code' => $this->data['code'],
            ]);
        }

        return new Warehouse();
    }

    protected function beforeCreate(): void
    {
        $this->record->team_id = $this->options['teamId'] ?? null;

        if ($this->record->is_active === null) {
            $this->record->is_active = true;
        }
    }
}
