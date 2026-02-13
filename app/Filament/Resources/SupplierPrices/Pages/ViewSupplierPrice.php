<?php

declare(strict_types=1);

namespace App\Filament\Resources\SupplierPrices\Pages;

use App\Filament\Resources\SupplierPrices\SupplierPriceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

final class ViewSupplierPrice extends ViewRecord
{
    protected static string $resource = SupplierPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
