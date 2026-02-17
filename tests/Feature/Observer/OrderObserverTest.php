<?php

declare(strict_types=1);

use App\Enums\OrderStatus;
use App\Events\OrderDelivered;
use App\Models\Order;
use Illuminate\Support\Facades\Event;

it('dispatches OrderDelivered event when order status changes to delivered', function (): void {
    Event::fake([OrderDelivered::class]);

    $order = Order::factory()->purchaseOrder()->create([
        'status' => OrderStatus::CONFIRMED,
    ]);

    $order->update(['status' => OrderStatus::DELIVERED]);

    Event::assertDispatched(OrderDelivered::class, fn (OrderDelivered $event): bool => $event->order->is($order));
});

it('does not dispatch OrderDelivered event for non-delivered status changes', function (): void {
    Event::fake([OrderDelivered::class]);

    $order = Order::factory()->purchaseOrder()->create([
        'status' => OrderStatus::DRAFT,
    ]);

    $order->update(['status' => OrderStatus::CONFIRMED]);

    Event::assertNotDispatched(OrderDelivered::class);
});

it('does not dispatch OrderDelivered event when status is not changed', function (): void {
    Event::fake([OrderDelivered::class]);

    $order = Order::factory()->purchaseOrder()->create([
        'status' => OrderStatus::DELIVERED,
    ]);

    $order->update(['notes' => 'Updated notes']);

    Event::assertNotDispatched(OrderDelivered::class);
});

it('dispatches OrderDelivered event for sales orders too', function (): void {
    Event::fake([OrderDelivered::class]);

    $order = Order::factory()->salesOrder()->create([
        'status' => OrderStatus::CONFIRMED,
    ]);

    $order->update(['status' => OrderStatus::DELIVERED]);

    Event::assertDispatched(OrderDelivered::class, fn (OrderDelivered $event): bool => $event->order->is($order));
});
