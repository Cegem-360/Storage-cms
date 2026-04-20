<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Filament\Imports\Concerns\LocalizedNotifications;
use App\Filament\Imports\Concerns\NormalizesNumericState;
use App\Models\Batch;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Forms\Components\Checkbox;

final class BatchImporter extends Importer
{
    use LocalizedNotifications;
    use NormalizesNumericState;

    protected static ?string $model = Batch::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('batch_number')
                ->requiredMapping()
                ->rules(['required', 'max:100']),
            ImportColumn::make('product')
                ->relationship(resolveUsing: ['sku', 'name'])
                ->helperText(__('Product SKU or name'))
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('supplier')
                ->relationship(resolveUsing: ['code', 'company_name'])
                ->helperText(__('Supplier code or company name'))
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('manufacture_date')
                ->rules(['nullable', 'date']),
            ImportColumn::make('expiry_date')
                ->rules(['nullable', 'date']),
            ImportColumn::make('quantity')
                ->requiredMapping()
                ->numeric()
                ->castStateUsing(fn (mixed $originalState): ?int => self::normalizeInt($originalState))
                ->rules(['required', 'integer']),
            ImportColumn::make('quality_status')
                ->rules(['max:50']),
        ];
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Checkbox::make('updateExisting')
                ->label(__('Update existing records')),
        ];
    }

    public function resolveRecord(): Batch
    {
        if ($this->options['updateExisting'] ?? false) {
            return Batch::query()->firstOrNew([
                'batch_number' => $this->data['batch_number'],
            ]);
        }

        return new Batch();
    }

    protected function beforeCreate(): void
    {
        $this->record->team_id = $this->options['teamId'] ?? null;
    }
}
