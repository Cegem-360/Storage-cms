<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Models\Warehouse;
use Filament\Actions\Imports\ImportColumn;
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
                ->boolean()
                ->rules(['required', 'boolean']),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Raktárak importálása befejeződött. '.Number::format($import->successful_rows).' '.str('sor')->plural($import->successful_rows).' importálva.';

        if (($failedRowsCount = $import->getFailedRowsCount()) !== 0) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('sor')->plural($failedRowsCount).' importálása sikertelen.';
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
}
