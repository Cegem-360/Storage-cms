<?php

declare(strict_types=1);

namespace App\Filament\Resources\IntrastatInbounds\Pages;

use App\Filament\Resources\IntrastatInbounds\IntrastatInboundResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Override;

final class EditIntrastatInbound extends EditRecord
{
    protected static string $resource = IntrastatInboundResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
