<?php

declare(strict_types=1);

namespace App\Filament\Imports\Columns;

use Filament\Actions\Imports\ImportColumn as BaseImportColumn;

final class ImportColumn extends BaseImportColumn
{
    public function localizedBoolean(?bool $default = null): static
    {
        $this->boolean();

        $this->castStateUsing(function (?bool $state, mixed $originalState) use ($default): ?bool {
            if ($originalState !== null) {
                return match (mb_strtolower((string) $originalState)) {
                    'igen', 'i' => true,
                    'nem' => false,
                    default => $state,
                };
            }

            return $state ?? $default;
        });

        return $this;
    }
}
