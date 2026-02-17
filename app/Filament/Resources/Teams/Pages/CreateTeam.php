<?php

declare(strict_types=1);

namespace App\Filament\Resources\Teams\Pages;

use App\Filament\Resources\Teams\TeamResource;
use Filament\Resources\Pages\CreateRecord;
use Override;

final class CreateTeam extends CreateRecord
{
    protected static string $resource = TeamResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    #[Override]
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data['ai_monthly_token_limit']);

        return $data;
    }

    protected function afterCreate(): void
    {
        if (isset($this->data['ai_monthly_token_limit'])) {
            $this->record->setSetting(
                'ai_monthly_token_limit',
                (int) $this->data['ai_monthly_token_limit'],
            );
        }
    }
}
