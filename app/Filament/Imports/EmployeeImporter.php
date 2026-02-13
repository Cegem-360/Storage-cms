<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Models\Employee;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Override;

final class EmployeeImporter extends Importer
{
    protected static ?string $model = Employee::class;

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
            ImportColumn::make('phone')
                ->rules(['max:255']),
            ImportColumn::make('position')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('warehouse')
                ->relationship()
                ->requiredMapping()
                ->rules(['required']),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Dolgozók importálása befejeződött. '.Number::format($import->successful_rows).' '.str('sor')->plural($import->successful_rows).' importálva.';

        if (($failedRowsCount = $import->getFailedRowsCount()) !== 0) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('sor')->plural($failedRowsCount).' importálása sikertelen.';
        }

        return $body;
    }

    #[Override]
    public function resolveRecord(): Employee
    {
        return Employee::query()->firstOrNew([
            'email' => $this->data['email'],
        ]);
    }
}
