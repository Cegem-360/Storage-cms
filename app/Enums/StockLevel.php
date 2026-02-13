<?php

declare(strict_types=1);

namespace App\Enums;

use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum StockLevel: string implements HasColor, HasIcon, HasLabel
{
    case OUT_OF_STOCK = 'out_of_stock';
    case LOW_STOCK = 'low_stock';
    case NORMAL = 'normal';
    case OVERSTOCK = 'overstock';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::OUT_OF_STOCK => __('Out of Stock'),
            self::LOW_STOCK => __('Low Stock'),
            self::NORMAL => __('Stock level OK'),
            self::OVERSTOCK => __('Overstock'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::OUT_OF_STOCK => 'danger',
            self::LOW_STOCK => 'warning',
            self::NORMAL => 'success',
            self::OVERSTOCK => 'info',
        };
    }

    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return match ($this) {
            self::OUT_OF_STOCK => Heroicon::OutlinedXCircle,
            self::LOW_STOCK => Heroicon::OutlinedExclamationTriangle,
            self::NORMAL => Heroicon::OutlinedCheckCircle,
            self::OVERSTOCK => Heroicon::OutlinedArrowTrendingUp,
        };
    }
}
