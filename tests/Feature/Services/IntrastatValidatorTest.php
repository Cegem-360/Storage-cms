<?php

declare(strict_types=1);

use App\Models\IntrastatDeclaration;
use App\Models\IntrastatLine;
use App\Services\IntrastatValidator;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->validator = new IntrastatValidator();
});

it('validates declaration with no lines', function (): void {
    $declaration = IntrastatDeclaration::factory()->create();

    $errors = $this->validator->validateDeclaration($declaration);

    expect($errors)->toContain('Declaration must have at least one line');
});

it('validates line with invalid CN code', function (): void {
    $declaration = IntrastatDeclaration::factory()->create();

    IntrastatLine::factory()->create([
        'intrastat_declaration_id' => $declaration->id,
        'cn_code' => '123',
    ]);

    $errors = $this->validator->validateDeclaration($declaration);

    expect($errors)->toHaveCount(1)
        ->and($errors[0])->toContain('KN kód kötelező, pontosan 8 számjegyből kell állnia');
});

it('validates line with invalid net mass', function (): void {
    $declaration = IntrastatDeclaration::factory()->create();

    IntrastatLine::factory()->create([
        'intrastat_declaration_id' => $declaration->id,
        'cn_code' => '12345678',
        'net_mass' => 0.0001,
    ]);

    $errors = $this->validator->validateDeclaration($declaration);

    expect($errors)->toContain('Sor 1: Nettó tömeg kötelező, minimum 0.001 kg');
});

it('validates line with invalid invoice value', function (): void {
    $declaration = IntrastatDeclaration::factory()->create();

    IntrastatLine::factory()->create([
        'intrastat_declaration_id' => $declaration->id,
        'cn_code' => '12345678',
        'net_mass' => 1.0,
        'invoice_value' => 0,
    ]);

    $errors = $this->validator->validateDeclaration($declaration);

    expect($errors)->toContain('Sor 1: Számlaérték kötelező, minimum 1 HUF');
});

it('validates line with invalid country code', function (): void {
    $declaration = IntrastatDeclaration::factory()->create();

    IntrastatLine::factory()->create([
        'intrastat_declaration_id' => $declaration->id,
        'cn_code' => '12345678',
        'net_mass' => 1.0,
        'invoice_value' => 1000,
        'statistical_value' => 1000,
        'country_of_consignment' => 'US',
    ]);

    $errors = $this->validator->validateDeclaration($declaration);

    expect($errors)->toContain('Sor 1: Feladás országa érvénytelen (csak EU tagállamok, HU kivételével)');
});
