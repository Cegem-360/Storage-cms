<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Filament\Imports\Columns\ImportColumn;
use App\Models\User;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Override;

final class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),
            ImportColumn::make('email_verified_at')
                ->rules(['email', 'datetime']),
            ImportColumn::make('password')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('is_super_admin')
                ->requiredMapping()
                ->localizedBoolean(default: false)
                ->rules(['required', 'boolean']),
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
    public function resolveRecord(): User
    {
        return User::query()->firstOrNew([
            'email' => $this->data['email'],
        ]);
    }
}
