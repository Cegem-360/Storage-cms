<?php

declare(strict_types=1);

namespace App\Filament\Imports\Concerns;

trait NormalizesNumericState
{
    public static function normalizeFloat(int|float|string|null $state): ?float
    {
        if ($state === null || $state === '') {
            return null;
        }

        if (is_int($state) || is_float($state)) {
            return (float) $state;
        }

        $normalized = preg_replace('/\s+/', '', $state) ?? '';

        $lastDot = mb_strrpos($normalized, '.');
        $lastComma = mb_strrpos($normalized, ',');
        $lastSeparator = max($lastDot === false ? -1 : $lastDot, $lastComma === false ? -1 : $lastComma);

        if ($lastSeparator >= 0) {
            $integerPart = str_replace([',', '.'], '', mb_substr($normalized, 0, $lastSeparator));
            $decimalPart = mb_substr($normalized, $lastSeparator + 1);
            $normalized = $integerPart.'.'.$decimalPart;
        }

        if (! is_numeric($normalized)) {
            return null;
        }

        return (float) $normalized;
    }

    public static function normalizeInt(int|float|string|null $state): ?int
    {
        $float = self::normalizeFloat($state);

        return $float === null ? null : (int) round($float);
    }
}
