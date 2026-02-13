<?php

declare(strict_types=1);

namespace App\Filament\Resources\SupplierPrices\Pages;

use App\Filament\Resources\SupplierPrices\SupplierPriceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

final class EditSupplierPrice extends EditRecord
{
    protected static string $resource = SupplierPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
