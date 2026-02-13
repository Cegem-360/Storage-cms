<?php

declare(strict_types=1);

use App\Services\QrCodeService;

it('generates valid SVG', function (): void {
    $svg = QrCodeService::generateSvg('https://example.com');

    expect($svg)->toBeString();
    expect($svg)->toContain('<svg');
    expect($svg)->toContain('</svg>');
});

it('generates SVG with custom size', function (): void {
    $svg = QrCodeService::generateSvg('test', 300);

    expect($svg)->toContain('width="300"');
});

it('generates PNG binary data', function (): void {
    $png = QrCodeService::generatePng('test-data');

    expect($png)->toBeString();
    expect(mb_strlen($png))->toBeGreaterThan(0);
});

it('generates base64 encoded PNG', function (): void {
    $base64 = QrCodeService::generateBase64Png('test');

    expect($base64)->toBeString();
    expect(base64_decode($base64, true))->not->toBeFalse();
});

it('generates data URI with correct prefix', function (): void {
    $dataUri = QrCodeService::generateDataUri('test');

    expect($dataUri)->toStartWith('data:image/png;base64,');

    $base64Part = str_replace('data:image/png;base64,', '', $dataUri);
    expect(base64_decode($base64Part, true))->not->toBeFalse();
});
