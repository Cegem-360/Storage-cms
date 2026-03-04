<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CustomerType: string implements HasLabel
{
    case RETAIL = 'RETAIL';
    case WHOLESALE = 'WHOLESALE';
    case DISTRIBUTOR = 'DISTRIBUTOR';
    case INTERNAL = 'INTERNAL';
    case VIP = 'VIP';

    public function getLabel(): string
    {
        return match ($this) {
            self::RETAIL => __('Retail Customer'),
            self::WHOLESALE => __('Wholesale Customer'),
            self::DISTRIBUTOR => __('Distributor'),
            self::INTERNAL => __('Internal'),
            self::VIP => __('VIP Customer'),
        };
    }

    public function discountRate(): float
    {
        return match ($this) {
            self::RETAIL => 0.0,
            self::WHOLESALE => 5.0,
            self::DISTRIBUTOR => 10.0,
            self::INTERNAL => 0.0,
            self::VIP => 15.0,
        };
    }
}
