<?php

declare(strict_types=1);

namespace App\Services;

final class PostalCodeLookupService
{
    /** @var array<string, string>|null */
    private static ?array $postalCodes = null;

    public static function lookup(string $postalCode): ?string
    {
        return self::getPostalCodes()[$postalCode] ?? null;
    }

    /**
     * @return array<string, string>
     */
    private static function getPostalCodes(): array
    {
        if (self::$postalCodes === null) {
            self::$postalCodes = require database_path('data/hu_postal_codes.php');
        }

        return self::$postalCodes;
    }
}
