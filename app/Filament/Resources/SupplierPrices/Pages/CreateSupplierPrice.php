<?php

declare(strict_types=1);

namespace App\Filament\Resources\SupplierPrices\Pages;

use App\Filament\Resources\SupplierPrices\SupplierPriceResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateSupplierPrice extends CreateRecord
{
    protected static string $resource = SupplierPriceResource::class;
}
