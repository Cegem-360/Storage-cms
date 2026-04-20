<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Filament\Imports\Columns\ImportColumn;
use App\Filament\Imports\Concerns\LocalizedNotifications;
use App\Models\User;
use Filament\Actions\Imports\Importer;
use Filament\Forms\Components\Checkbox;

final class UserImporter extends Importer
{
    use LocalizedNotifications;

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

    public static function getOptionsFormComponents(): array
    {
        return [
            Checkbox::make('updateExisting')
                ->label(__('Update existing records')),
        ];
    }

    public function resolveRecord(): User
    {
        if ($this->options['updateExisting'] ?? false) {
            return User::query()->firstOrNew([
                'email' => $this->data['email'],
            ]);
        }

        return new User();
    }

    protected function beforeCreate(): void
    {
        if ($this->record->is_active === null) {
            $this->record->is_active = true;
        }

        if ($this->record->is_super_admin === null) {
            $this->record->is_super_admin = false;
        }
    }
}
