<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum NavigationGroup: string implements HasLabel
{
    case INVENTORY_MANAGEMENT = 'Inventory Management';
    case SALES = 'Sales';
    case REPORTS = 'Reports';
    case ADMINISTRATION = 'Administration';
    case INTRASTAT = 'Intrastat';

    public function getLabel(): string
    {
        return match ($this) {
            self::INVENTORY_MANAGEMENT => __('Inventory Management'),
            self::SALES => __('Sales'),
            self::REPORTS => __('Reports'),
            self::ADMINISTRATION => __('Administration'),
            self::INTRASTAT => __('Intrastat'),
        };
    }

    public function getSort(): int
    {
        return match ($this) {
            self::INVENTORY_MANAGEMENT => 10,
            self::SALES => 20,
            self::REPORTS => 30,
            self::ADMINISTRATION => 40,
            self::INTRASTAT => 50,
        };
    }
}
