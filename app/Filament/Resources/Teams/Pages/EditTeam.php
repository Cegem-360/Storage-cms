<?php

declare(strict_types=1);

namespace App\Filament\Resources\Teams\Pages;

use App\Filament\Resources\Teams\TeamResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Override;

final class EditTeam extends EditRecord
{
    protected static string $resource = TeamResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    #[Override]
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['ai_monthly_token_limit'] = (int) $this->record->getSetting('ai_monthly_token_limit', 0);

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    #[Override]
    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['ai_monthly_token_limit']);

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->setSetting(
            'ai_monthly_token_limit',
            (int) $this->data['ai_monthly_token_limit'],
        );
    }
}
