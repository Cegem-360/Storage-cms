<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ProductCondition: string implements HasColor, HasLabel
{
    case GOOD = 'good';
    case NEW = 'new';
    case MINOR_DAMAGE = 'minor_damage';
    case DAMAGED = 'damaged';
    case DEFECTIVE = 'defective';
    case EXPIRED = 'expired';

    public function getLabel(): string
    {
        return match ($this) {
            self::GOOD => __('Good'),
            self::NEW => __('New'),
            self::MINOR_DAMAGE => __('Minor Damage'),
            self::DAMAGED => __('Damaged'),
            self::DEFECTIVE => __('Defective'),
            self::EXPIRED => __('Expired'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::GOOD => 'success',
            self::NEW => 'primary',
            self::MINOR_DAMAGE => 'warning',
            self::DAMAGED => 'danger',
            self::DEFECTIVE => 'danger',
            self::EXPIRED => 'danger',
        };
    }

    public function canBeRestocked(): bool
    {
        return match ($this) {
            self::GOOD, self::MINOR_DAMAGE, self::NEW => true,
            default => false,
        };
    }

    public function requiresDisposal(): bool
    {
        return match ($this) {
            self::DAMAGED, self::DEFECTIVE, self::EXPIRED => true,
            default => false,
        };
    }
}
