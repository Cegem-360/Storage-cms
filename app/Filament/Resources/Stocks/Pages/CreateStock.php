<?php

declare(strict_types=1);

namespace App\Filament\Resources\Stocks\Pages;

use App\Filament\Resources\Stocks\StockResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateStock extends CreateRecord
{
    protected static string $resource = StockResource::class;
}
