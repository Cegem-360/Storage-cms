<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum InvoiceStatus: string implements HasColor, HasLabel
{
    case DRAFT = 'draft';
    case ISSUED = 'issued';
    case SENT = 'sent';
    case PAID = 'paid';
    case PARTIALLY_PAID = 'partially_paid';
    case OVERDUE = 'overdue';
    case CANCELLED = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::DRAFT => __('Draft'),
            self::ISSUED => __('Issued'),
            self::SENT => __('Sent'),
            self::PAID => __('Paid'),
            self::PARTIALLY_PAID => __('Partially Paid'),
            self::OVERDUE => __('Overdue'),
            self::CANCELLED => __('Cancelled'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::ISSUED => 'blue',
            self::SENT => 'indigo',
            self::PAID => 'green',
            self::PARTIALLY_PAID => 'yellow',
            self::OVERDUE => 'red',
            self::CANCELLED => 'red',
        };
    }

    public function isEditable(): bool
    {
        return match ($this) {
            self::DRAFT, self::ISSUED => true,
            default => false,
        };
    }

    public function isFinal(): bool
    {
        return match ($this) {
            self::PAID, self::CANCELLED => true,
            default => false,
        };
    }
}
