<?php

declare(strict_types=1);

use App\Enums\CountryCode;

it('creates country code from valid address array', function () {
    $address = ['country' => 'DE', 'city' => 'Berlin'];

    expect(CountryCode::fromAddress($address))->toBe(CountryCode::DE);
});

it('returns null for address without country key', function () {
    $address = ['city' => 'Berlin', 'zip' => '10115'];

    expect(CountryCode::fromAddress($address))->toBeNull();
});

it('returns null for null address', function () {
    expect(CountryCode::fromAddress(null))->toBeNull();
});

it('returns null for string address', function () {
    expect(CountryCode::fromAddress('Berlin, Germany'))->toBeNull();
});

it('returns null for invalid country code in address', function () {
    $address = ['country' => 'INVALID'];

    expect(CountryCode::fromAddress($address))->toBeNull();
});

it('identifies EU member states correctly', function () {
    expect(CountryCode::DE->isEuMember())->toBeTrue()
        ->and(CountryCode::HU->isEuMember())->toBeTrue()
        ->and(CountryCode::US->isEuMember())->toBeFalse()
        ->and(CountryCode::XI->isEuMember())->toBeFalse();
});
