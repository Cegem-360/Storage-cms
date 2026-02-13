<?php

declare(strict_types=1);

use App\Models\Receipt;
use App\Models\ReceiptLine;

it('calculates variance when received matches expected', function (): void {
    $line = ReceiptLine::factory()->create([
        'quantity_expected' => 50,
        'quantity_received' => 50,
    ])->fresh();

    expect($line->variance)->toBe(0);
});

it('calculates positive variance when over-received', function (): void {
    $line = ReceiptLine::factory()->create([
        'quantity_expected' => 30,
        'quantity_received' => 35,
    ])->fresh();

    expect($line->variance)->toBe(5);
});

it('calculates negative variance when under-received', function (): void {
    $line = ReceiptLine::factory()->withVariance()->create([
        'quantity_expected' => 40,
        'quantity_received' => 32,
    ])->fresh();

    expect($line->variance)->toBe(-8);
});

it('calculates line total from received quantity', function (): void {
    $line = ReceiptLine::factory()->create([
        'quantity_received' => 10,
        'unit_price' => 25.50,
    ])->fresh();

    expect($line->line_total)->toBe(255.0);
});

it('returns zero line total when nothing received', function (): void {
    $line = ReceiptLine::factory()->create([
        'quantity_expected' => 20,
        'quantity_received' => 0,
        'unit_price' => 100.00,
    ])->fresh();

    expect($line->line_total)->toBe(0.0);
});

it('detects discrepancy correctly', function (): void {
    $matchLine = ReceiptLine::factory()->create([
        'quantity_expected' => 10,
        'quantity_received' => 10,
    ]);

    $discrepantLine = ReceiptLine::factory()->create([
        'quantity_expected' => 10,
        'quantity_received' => 8,
    ]);

    expect($matchLine->isDiscrepant())->toBeFalse();
    expect($discrepantLine->isDiscrepant())->toBeTrue();
});

it('belongs to receipt', function (): void {
    $receipt = Receipt::factory()->create();
    $line = ReceiptLine::factory()->create(['receipt_id' => $receipt->id]);

    expect($line->receipt)->toBeInstanceOf(Receipt::class);
    expect($line->receipt->id)->toBe($receipt->id);
});
