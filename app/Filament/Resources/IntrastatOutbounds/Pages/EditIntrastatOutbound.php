<?php

declare(strict_types=1);

namespace App\Filament\Resources\IntrastatOutbounds\Pages;

use App\Filament\Resources\IntrastatOutbounds\IntrastatOutboundResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Override;

final class EditIntrastatOutbound extends EditRecord
{
    protected static string $resource = IntrastatOutboundResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
