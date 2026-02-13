<?php

declare(strict_types=1);

use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Product;

it('calculates subtotal without discount', function (): void {
    $line = OrderLine::factory()->create([
        'quantity' => 3,
        'unit_price' => 100.00,
        'discount_percent' => 0,
    ])->fresh();

    expect($line->subtotal)->toBe(300.0);
});

it('calculates subtotal with discount', function (): void {
    $line = OrderLine::factory()->create([
        'quantity' => 2,
        'unit_price' => 200.00,
        'discount_percent' => 10,
    ])->fresh();

    // 2 * 200 * (1 - 10/100) = 400 * 0.9 = 360
    expect($line->subtotal)->toBe(360.0);
});

it('calculates discount amount', function (): void {
    $line = OrderLine::factory()->create([
        'quantity' => 4,
        'unit_price' => 50.00,
        'discount_percent' => 25,
    ])->fresh();

    // 4 * 50 * (25/100) = 200 * 0.25 = 50
    expect($line->discount_amount)->toBe(50.0);
});

it('returns zero discount amount when no discount', function (): void {
    $line = OrderLine::factory()->withoutDiscount()->create([
        'quantity' => 5,
        'unit_price' => 100.00,
    ])->fresh();

    expect($line->discount_amount)->toBe(0.0);
});

it('has subtotal and discount amount that sum to gross total', function (): void {
    $line = OrderLine::factory()->create([
        'quantity' => 3,
        'unit_price' => 150.00,
        'discount_percent' => 20,
    ])->fresh();

    $grossTotal = $line->quantity * $line->unit_price;

    expect($line->subtotal + $line->discount_amount)->toBe((float) $grossTotal);
});

it('belongs to order', function (): void {
    $order = Order::factory()->create();
    $line = OrderLine::factory()->create(['order_id' => $order->id]);

    expect($line->order)->toBeInstanceOf(Order::class);
    expect($line->order->id)->toBe($order->id);
});

it('belongs to product', function (): void {
    $product = Product::factory()->create();
    $line = OrderLine::factory()->create(['product_id' => $product->id]);

    expect($line->product)->toBeInstanceOf(Product::class);
    expect($line->product->id)->toBe($product->id);
});
