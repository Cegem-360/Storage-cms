<?php

declare(strict_types=1);

use App\Enums\ReceiptStatus;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\ReceiptLine;

it('calculates total from receipt lines', function (): void {
    $receipt = Receipt::factory()->create();

    ReceiptLine::factory()->create([
        'receipt_id' => $receipt->id,
        'quantity_received' => 5,
        'unit_price' => 100.00,
    ]);

    ReceiptLine::factory()->create([
        'receipt_id' => $receipt->id,
        'quantity_received' => 3,
        'unit_price' => 50.00,
    ]);

    // (5 * 100) + (3 * 50) = 500 + 150 = 650
    expect($receipt->calculated_total)->toBe(650.0);
});

it('returns zero calculated total when no lines exist', function (): void {
    $receipt = Receipt::factory()->create();

    expect($receipt->calculated_total)->toBe(0.0);
});

it('refreshes total amount to database', function (): void {
    $receipt = Receipt::factory()->create(['total_amount' => 0]);

    ReceiptLine::factory()->create([
        'receipt_id' => $receipt->id,
        'quantity_received' => 4,
        'unit_price' => 75.00,
    ]);

    $receipt->refreshTotal();

    expect($receipt->fresh()->total_amount)->toBe('300.00');
});

it('can add receipt line and refresh total', function (): void {
    $receipt = Receipt::factory()->create(['total_amount' => 0]);

    $line = new ReceiptLine([
        'product_id' => Product::factory()->create()->id,
        'warehouse_id' => $receipt->warehouse_id,
        'quantity_expected' => 10,
        'quantity_received' => 10,
        'unit_price' => 30.00,
        'condition' => 'GOOD',
    ]);

    $receipt->addLine($line);

    expect($receipt->receiptLines)->toHaveCount(1);
    expect($receipt->fresh()->total_amount)->toBe('300.00');
});

it('can remove receipt line and refresh total', function (): void {
    $receipt = Receipt::factory()->create(['total_amount' => 500]);

    $line = ReceiptLine::factory()->create([
        'receipt_id' => $receipt->id,
        'quantity_received' => 5,
        'unit_price' => 100.00,
    ]);

    $receipt->removeLine($line);

    expect($receipt->receiptLines)->toHaveCount(0);
    expect($receipt->fresh()->total_amount)->toBe('0.00');
});

it('can confirm receipt', function (): void {
    $receipt = Receipt::factory()->create(['status' => ReceiptStatus::PENDING]);

    $receipt->confirm();

    expect($receipt->fresh()->status)->toBe(ReceiptStatus::CONFIRMED);
});

it('can reject receipt', function (): void {
    $receipt = Receipt::factory()->create(['status' => ReceiptStatus::PENDING]);

    $receipt->reject();

    expect($receipt->fresh()->status)->toBe(ReceiptStatus::REJECTED);
});

it('casts status to enum', function (): void {
    $receipt = Receipt::factory()->create(['status' => ReceiptStatus::PENDING]);

    expect($receipt->status)->toBeInstanceOf(ReceiptStatus::class);
    expect($receipt->status)->toBe(ReceiptStatus::PENDING);
});

it('has many receipt lines', function (): void {
    $receipt = Receipt::factory()->create();

    ReceiptLine::factory()->count(3)->create(['receipt_id' => $receipt->id]);

    expect($receipt->receiptLines)->toHaveCount(3);
});
