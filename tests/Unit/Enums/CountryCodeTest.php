<?php

declare(strict_types=1);

use App\Enums\CountryCode;

it('creates country code from valid address array', function (): void {
    $address = ['country' => 'DE', 'city' => 'Berlin'];

    expect(CountryCode::fromAddress($address))->toBe(CountryCode::DE);
});

it('returns null for address without country key', function (): void {
    $address = ['city' => 'Berlin', 'zip' => '10115'];

    expect(CountryCode::fromAddress($address))->toBeNull();
});

it('returns null for null address', function (): void {
    expect(CountryCode::fromAddress(null))->toBeNull();
});

it('returns null for string address', function (): void {
    expect(CountryCode::fromAddress('Berlin, Germany'))->toBeNull();
});

it('returns null for invalid country code in address', function (): void {
    $address = ['country' => 'INVALID'];

    expect(CountryCode::fromAddress($address))->toBeNull();
});

it('identifies EU member states correctly', function (): void {
    expect(CountryCode::DE->isEuMember())->toBeTrue()
        ->and(CountryCode::HU->isEuMember())->toBeTrue()
        ->and(CountryCode::US->isEuMember())->toBeFalse()
        ->and(CountryCode::XI->isEuMember())->toBeFalse();
});
