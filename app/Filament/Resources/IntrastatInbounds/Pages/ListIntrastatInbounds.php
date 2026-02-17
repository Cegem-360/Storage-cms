<?php

declare(strict_types=1);

namespace App\Filament\Resources\IntrastatInbounds\Pages;

use App\Filament\Resources\IntrastatInbounds\IntrastatInboundResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Override;

final class ListIntrastatInbounds extends ListRecords
{
    protected static string $resource = IntrastatInboundResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
