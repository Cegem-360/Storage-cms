<?php

declare(strict_types=1);

use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Order;
use App\Models\Product;
use App\Models\Receipt;

uses()->group('invoice-management');

test('invoice can have lines with calculated totals', function (): void {
    $invoice = Invoice::factory()->create();
    $product = Product::factory()->create();

    InvoiceLine::factory()->create([
        'invoice_id' => $invoice->id,
        'product_id' => $product->id,
        'quantity' => 10,
        'unit_price' => 1000,
        'discount_percent' => 0,
        'tax_percent' => 27,
    ]);

    $invoice->refresh();
    $line = $invoice->invoiceLines->first();

    expect($line->subtotal)->toBe(10000.0)
        ->and($line->tax_amount)->toBe(2700.0)
        ->and($line->line_total)->toBe(12700.0);
});

test('invoice belongs to order', function (): void {
    $order = Order::factory()->create();
    $invoice = Invoice::factory()->create(['order_id' => $order->id]);

    expect($invoice->order->id)->toBe($order->id);
});

test('invoice belongs to receipt', function (): void {
    $receipt = Receipt::factory()->create();
    $invoice = Invoice::factory()->create(['receipt_id' => $receipt->id]);

    expect($invoice->receipt->id)->toBe($receipt->id);
});

test('refreshTotal updates total_amount', function (): void {
    $invoice = Invoice::factory()->create();
    $product = Product::factory()->create();

    InvoiceLine::factory()->create([
        'invoice_id' => $invoice->id,
        'product_id' => $product->id,
        'quantity' => 5,
        'unit_price' => 2000,
        'discount_percent' => 10,
        'tax_percent' => 27,
    ]);

    $invoice->refresh();
    $invoice->refreshTotal();
    $invoice->refresh();

    // 5 * 2000 * (1 - 10/100) = 9000 subtotal
    // 9000 * 27/100 = 2430 tax
    // 9000 * (1 + 27/100) = 11430 total
    expect((float) $invoice->subtotal)->toBe(9000.0)
        ->and((float) $invoice->tax_total)->toBe(2430.0)
        ->and((float) $invoice->total_amount)->toBe(11430.0);
});

test('invoice line calculates discount correctly', function (): void {
    $invoice = Invoice::factory()->create();

    InvoiceLine::factory()->create([
        'invoice_id' => $invoice->id,
        'quantity' => 100,
        'unit_price' => 500,
        'discount_percent' => 20,
        'tax_percent' => 27,
    ]);

    $line = $invoice->invoiceLines()->first();

    // 100 * 500 * (1 - 20/100) = 40000
    expect($line->subtotal)->toBe(40000.0)
        ->and($line->tax_amount)->toBe(10800.0)
        ->and($line->line_total)->toBe(50800.0);
});

test('order can have invoices', function (): void {
    $order = Order::factory()->create();

    Invoice::factory()->count(2)->create(['order_id' => $order->id]);

    expect($order->invoices)->toHaveCount(2);
});

test('receipt can have invoices', function (): void {
    $receipt = Receipt::factory()->create();

    Invoice::factory()->create(['receipt_id' => $receipt->id]);

    expect($receipt->invoices)->toHaveCount(1);
});
