<?php

declare(strict_types=1);

use App\Enums\StockTransactionType;
use App\Events\InboundStockReceived;
use App\Models\Order;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\Event;

it('dispatches InboundStockReceived event for inbound transaction with order reference', function (): void {
    Event::fake([InboundStockReceived::class]);

    $order = Order::factory()->purchaseOrder()->create();
    $product = Product::factory()->create();

    $transaction = StockTransaction::factory()->create([
        'type' => StockTransactionType::INBOUND,
        'product_id' => $product->id,
        'reference_type' => Order::class,
        'reference_id' => $order->id,
    ]);

    Event::assertDispatched(InboundStockReceived::class, fn (InboundStockReceived $event): bool => $event->stockTransaction->is($transaction));
});

it('does not dispatch event for outbound stock transaction', function (): void {
    Event::fake([InboundStockReceived::class]);

    StockTransaction::factory()->create([
        'type' => StockTransactionType::OUTBOUND,
    ]);

    Event::assertNotDispatched(InboundStockReceived::class);
});

it('does not dispatch event when transaction has no order reference', function (): void {
    Event::fake([InboundStockReceived::class]);

    StockTransaction::factory()->create([
        'type' => StockTransactionType::INBOUND,
        'reference_type' => null,
        'reference_id' => null,
    ]);

    Event::assertNotDispatched(InboundStockReceived::class);
});

it('does not dispatch event when reference type is not Order', function (): void {
    Event::fake([InboundStockReceived::class]);

    StockTransaction::factory()->create([
        'type' => StockTransactionType::INBOUND,
        'reference_type' => Receipt::class,
        'reference_id' => 1,
    ]);

    Event::assertNotDispatched(InboundStockReceived::class);
});
