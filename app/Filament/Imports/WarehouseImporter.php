<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Filament\Imports\Columns\ImportColumn;
use App\Models\Warehouse;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Override;

final class WarehouseImporter extends Importer
{
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
                ->rules(['integer']),
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
    public function resolveRecord(): Warehouse
    {
        return Warehouse::query()->firstOrNew([
            'code' => $this->data['code'],
        ]);
    }

    protected function beforeCreate(): void
    {
        $this->record->team_id = $this->options['teamId'] ?? null;
    }
}
