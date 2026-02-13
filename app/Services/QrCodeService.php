<?php

declare(strict_types=1);

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

final class QrCodeService
{
    public static function generateSvg(string $data, int $size = 200): string
    {
        return QrCode::format('svg')
            ->size($size)
            ->margin(1)
            ->generate($data)
            ->toHtml();
    }

    public static function generatePng(string $data, int $size = 200): string
    {
        return QrCode::format('png')
            ->size($size)
            ->margin(1)
            ->generate($data)
            ->toHtml();
    }

    public static function generateBase64Png(string $data, int $size = 200): string
    {
        $png = QrCode::format('png')
            ->size($size)
            ->margin(1)
            ->generate($data);

        return base64_encode($png->toHtml());
    }

    public static function generateDataUri(string $data, int $size = 200): string
    {
        return 'data:image/png;base64,'.self::generateBase64Png($data, $size);
    }
}
