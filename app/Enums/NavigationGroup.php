<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;

enum NavigationGroup: string implements HasIcon, HasLabel
{
    case MASTER_DATA = 'Master Data';
    case INVENTORY = 'Inventory';
    case PURCHASING = 'Purchasing';
    case REPORTS = 'Reports';
    case INTRASTAT = 'Intrastat';
    case ADMINISTRATION = 'Administration';
    case SETTINGS = 'Settings';

    public function getLabel(): string
    {
        return match ($this) {
            self::MASTER_DATA => __('Master Data'),
            self::INVENTORY => __('Inventory'),
            self::PURCHASING => __('Purchasing'),
            self::REPORTS => __('Reports'),
            self::INTRASTAT => __('Intrastat'),
            self::ADMINISTRATION => __('Administration'),
            self::SETTINGS => __('Settings'),
        };
    }

    public function getIcon(): ?Heroicon
    {
        return match ($this) {
            self::MASTER_DATA => Heroicon::OutlinedCircleStack,
            self::INVENTORY => Heroicon::OutlinedCubeTransparent,
            self::PURCHASING => Heroicon::OutlinedShoppingCart,
            self::REPORTS => Heroicon::OutlinedChartBar,
            self::INTRASTAT => Heroicon::OutlinedGlobeEuropeAfrica,
            self::ADMINISTRATION => Heroicon::OutlinedUserGroup,
            self::SETTINGS => Heroicon::OutlinedCog6Tooth,
        };
    }
}
