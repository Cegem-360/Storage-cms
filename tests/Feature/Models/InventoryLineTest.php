<?php

declare(strict_types=1);

use App\Enums\DiscrepancyType;
use App\Models\Inventory;
use App\Models\InventoryLine;

it('calculates zero variance when quantities match', function (): void {
    $line = InventoryLine::factory()->create([
        'system_quantity' => 50,
        'actual_quantity' => 50,
    ])->fresh();

    expect($line->variance_quantity)->toBe(0);
});

it('calculates positive variance for overage', function (): void {
    $line = InventoryLine::factory()->create([
        'system_quantity' => 40,
        'actual_quantity' => 50,
    ])->fresh();

    expect($line->variance_quantity)->toBe(10);
});

it('calculates negative variance for shortage', function (): void {
    $line = InventoryLine::factory()->create([
        'system_quantity' => 30,
        'actual_quantity' => 23,
    ])->fresh();

    expect($line->variance_quantity)->toBe(-7);
});

it('calculates variance value from quantity and cost', function (): void {
    $line = InventoryLine::factory()->create([
        'system_quantity' => 20,
        'actual_quantity' => 25,
        'unit_cost' => 100.00,
    ])->fresh();

    // (25 - 20) * 100 = 500
    expect($line->variance_value)->toBe(500.0);
});

it('calculates negative variance value for shortage', function (): void {
    $line = InventoryLine::factory()->create([
        'system_quantity' => 30,
        'actual_quantity' => 27,
        'unit_cost' => 50.00,
    ])->fresh();

    // (27 - 30) * 50 = -150
    expect($line->variance_value)->toBe(-150.0);
});

it('returns zero variance value when quantities match', function (): void {
    $line = InventoryLine::factory()->create([
        'system_quantity' => 15,
        'actual_quantity' => 15,
        'unit_cost' => 200.00,
    ])->fresh();

    expect($line->variance_value)->toBe(0.0);
});

it('detects variance correctly', function (): void {
    $matchLine = InventoryLine::factory()->create([
        'system_quantity' => 10,
        'actual_quantity' => 10,
    ]);

    $varianceLine = InventoryLine::factory()->create([
        'system_quantity' => 10,
        'actual_quantity' => 8,
    ]);

    expect($matchLine->hasVariance())->toBeFalse();
    expect($varianceLine->hasVariance())->toBeTrue();
});

it('determines discrepancy type', function (): void {
    $matchLine = InventoryLine::factory()->create([
        'system_quantity' => 10,
        'actual_quantity' => 10,
    ]);

    $shortageLine = InventoryLine::factory()->create([
        'system_quantity' => 10,
        'actual_quantity' => 7,
    ]);

    $overageLine = InventoryLine::factory()->create([
        'system_quantity' => 10,
        'actual_quantity' => 13,
    ]);

    expect($matchLine->getDiscrepancyType())->toBe(DiscrepancyType::MATCH);
    expect($shortageLine->getDiscrepancyType())->toBe(DiscrepancyType::SHORTAGE);
    expect($overageLine->getDiscrepancyType())->toBe(DiscrepancyType::OVERAGE);
});

it('belongs to inventory', function (): void {
    $inventory = Inventory::factory()->create();
    $line = InventoryLine::factory()->create(['inventory_id' => $inventory->id]);

    expect($line->inventory)->toBeInstanceOf(Inventory::class);
    expect($line->inventory->id)->toBe($inventory->id);
});
