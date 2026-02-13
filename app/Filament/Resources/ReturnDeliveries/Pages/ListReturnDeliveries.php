<?php

declare(strict_types=1);

namespace App\Filament\Resources\ReturnDeliveries\Pages;

use App\Filament\Resources\ReturnDeliveries\ReturnDeliveryResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Override;

final class ListReturnDeliveries extends ListRecords
{
    protected static string $resource = ReturnDeliveryResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('createWithSteps')
                ->label('Create with Wizard')
                ->icon(Heroicon::OutlinedSparkles)
                ->url(ReturnDeliveryResource::getUrl('create-with-steps')),
        ];
    }
}
