<?php

declare(strict_types=1);

namespace App\Services;

final class BarcodeService
{
    /**
     * Generate a valid EAN-13 barcode starting with the '200' prefix (internal use range).
     */
    public static function generateEan13(): string
    {
        $ean12 = '200'.mb_str_pad((string) random_int(0, 999999999), 9, '0', STR_PAD_LEFT);

        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += (int) $ean12[$i] * ($i % 2 === 0 ? 1 : 3);
        }

        return $ean12.(string) ((10 - ($sum % 10)) % 10);
    }
}
