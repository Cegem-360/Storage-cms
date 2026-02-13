<?php

declare(strict_types=1);

use App\Enums\ProductCondition;
use App\Models\ReturnDelivery;
use App\Models\ReturnDeliveryLine;

it('calculates line total', function (): void {
    $line = ReturnDeliveryLine::factory()->create([
        'quantity' => 3,
        'unit_price' => 150.00,
    ])->fresh();

    expect($line->line_total)->toBe(450.0);
});

it('returns zero line total for zero quantity', function (): void {
    $line = ReturnDeliveryLine::factory()->create([
        'quantity' => 0,
        'unit_price' => 200.00,
    ])->fresh();

    expect($line->line_total)->toBe(0.0);
});

it('determines if item can be restocked', function (): void {
    $goodLine = ReturnDeliveryLine::factory()->goodCondition()->create();
    $damagedLine = ReturnDeliveryLine::factory()->damaged()->create();

    expect($goodLine->canBeRestocked())->toBeTrue();
    expect($damagedLine->canBeRestocked())->toBeFalse();
});

it('determines if item requires disposal', function (): void {
    $goodLine = ReturnDeliveryLine::factory()->goodCondition()->create();
    $damagedLine = ReturnDeliveryLine::factory()->damaged()->create();

    expect($goodLine->requiresDisposal())->toBeFalse();
    expect($damagedLine->requiresDisposal())->toBeTrue();
});

it('belongs to return delivery', function (): void {
    $returnDelivery = ReturnDelivery::factory()->create();
    $line = ReturnDeliveryLine::factory()->create(['return_delivery_id' => $returnDelivery->id]);

    expect($line->returnDelivery)->toBeInstanceOf(ReturnDelivery::class);
    expect($line->returnDelivery->id)->toBe($returnDelivery->id);
});

it('casts condition to enum', function (): void {
    $line = ReturnDeliveryLine::factory()->goodCondition()->create();

    expect($line->condition)->toBeInstanceOf(ProductCondition::class);
    expect($line->condition)->toBe(ProductCondition::GOOD);
});
