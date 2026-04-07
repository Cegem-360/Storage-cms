<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Models\Batch;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Override;

final class BatchImporter extends Importer
{
    protected static ?string $model = Batch::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('batch_number')
                ->requiredMapping()
                ->rules(['required', 'max:100']),
            ImportColumn::make('product')
                ->relationship()
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('supplier')
                ->relationship()
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('manufacture_date')
                ->rules(['nullable', 'date']),
            ImportColumn::make('expiry_date')
                ->rules(['nullable', 'date']),
            ImportColumn::make('quantity')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('quality_status')
                ->rules(['max:50']),
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
    public function resolveRecord(): Batch
    {
        return Batch::query()->firstOrNew([
            'batch_number' => $this->data['batch_number'],
        ]);
    }

    protected function beforeCreate(): void
    {
        $this->record->team_id = $this->options['teamId'] ?? null;
    }
}
