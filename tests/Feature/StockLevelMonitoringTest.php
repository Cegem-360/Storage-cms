<?php

declare(strict_types=1);

use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Team;
use App\Models\User;
use App\Models\Warehouse;
use App\Notifications\LowStockAlert;
use App\Notifications\OverstockAlert;
use App\Notifications\ReorderPointReached;
use Illuminate\Support\Facades\Notification;

uses()->group('database');

it('can set minimum and maximum stock levels for a product in a warehouse', function (): void {
    $product = Product::factory()->create([
        'min_stock' => 10,
        'max_stock' => 100,
        'reorder_point' => 20,
    ]);

    $warehouse = Warehouse::factory()->create();

    $stock = Stock::factory()->create([
        'product_id' => $product->id,
        'warehouse_id' => $warehouse->id,
        'quantity' => 50,
        'minimum_stock' => 15,
        'maximum_stock' => 150,
    ]);

    expect($stock->minimum_stock)->toBe(15)
        ->and($stock->maximum_stock)->toBe(150)
        ->and($stock->quantity)->toBe(50);
});

it('detects low stock condition', function (): void {
    $product = Product::factory()->create();
    $warehouse = Warehouse::factory()->create();

    $stock = Stock::factory()->create([
        'product_id' => $product->id,
        'warehouse_id' => $warehouse->id,
        'quantity' => 5,
        'minimum_stock' => 10,
    ]);

    expect($stock->isLowStock())->toBeTrue();

    $stock->update(['quantity' => 15]);

    expect($stock->isLowStock())->toBeFalse();
});

it('detects reorder point reached for product', function (): void {
    $product = Product::factory()->create([
        'reorder_point' => 50,
    ]);

    $warehouse = Warehouse::factory()->create();

    Stock::factory()->create([
        'product_id' => $product->id,
        'warehouse_id' => $warehouse->id,
        'quantity' => 30,
    ]);

    $product->refresh();

    expect($product->needsReorder())->toBeTrue()
        ->and($product->getTotalStock())->toBe(30)
        ->and($product->calculateReorderQuantity())->toBeGreaterThan(0);
});

it('sends low stock alert notification when stock drops below minimum', function (): void {
    Notification::fake();

    User::factory()->create(['is_super_admin' => true, 'email' => 'admin@example.com']);

    $product = Product::factory()->create();
    $warehouse = Warehouse::factory()->create();

    $stock = Stock::factory()->create([
        'product_id' => $product->id,
        'warehouse_id' => $warehouse->id,
        'quantity' => 15,
        'minimum_stock' => 10,
    ]);

    $stock->update(['quantity' => 8]);

    Notification::assertSentTo(
        User::query()->where('is_super_admin', true)->get(),
        LowStockAlert::class,
        fn ($notification): bool => $notification->stock->id === $stock->id
    );
});

it('sends overstock alert notification when stock exceeds maximum', function (): void {
    Notification::fake();

    User::factory()->create(['is_super_admin' => true, 'email' => 'admin@example.com']);

    $product = Product::factory()->create();
    $warehouse = Warehouse::factory()->create();

    $stock = Stock::factory()->create([
        'product_id' => $product->id,
        'warehouse_id' => $warehouse->id,
        'quantity' => 80,
        'maximum_stock' => 100,
    ]);

    $stock->update(['quantity' => 120]);

    Notification::assertSentTo(
        User::query()->where('is_super_admin', true)->get(),
        OverstockAlert::class,
        fn ($notification): bool => $notification->stock->id === $stock->id
    );
});

it('does not send alert when stock level is normal', function (): void {
    Notification::fake();

    User::factory()->create(['is_super_admin' => true]);

    $product = Product::factory()->create();
    $warehouse = Warehouse::factory()->create();

    $stock = Stock::factory()->create([
        'product_id' => $product->id,
        'warehouse_id' => $warehouse->id,
        'quantity' => 50,
        'minimum_stock' => 10,
        'maximum_stock' => 100,
    ]);

    $stock->update(['quantity' => 60]);

    Notification::assertNothingSent();
});

it('calculates recommended reorder quantity correctly', function (): void {
    $product = Product::factory()->create([
        'max_stock' => 100,
        'reorder_point' => 20,
    ]);

    $warehouse = Warehouse::factory()->create();

    Stock::factory()->create([
        'product_id' => $product->id,
        'warehouse_id' => $warehouse->id,
        'quantity' => 15,
    ]);

    $product->refresh();

    $reorderQty = $product->calculateReorderQuantity();

    expect($reorderQty)->toBe(85) // 100 - 15
        ->and($product->needsReorder())->toBeTrue();
});

it('sends low stock alert to notification_email when configured', function (): void {
    Notification::fake();

    $team = Team::factory()->create();
    $user = User::factory()->create(['team_id' => $team->id]);

    $team->setSetting('notification_email', 'alerts@example.com');

    $supplier = Supplier::factory()->create(['team_id' => $team->id]);
    $product = Product::factory()->create([
        'team_id' => $team->id,
        'supplier_id' => $supplier->id,
        'reorder_point' => 0,
    ]);
    $warehouse = Warehouse::factory()->create(['team_id' => $team->id]);

    $stock = Stock::factory()->create([
        'team_id' => $team->id,
        'product_id' => $product->id,
        'warehouse_id' => $warehouse->id,
        'quantity' => 15,
        'minimum_stock' => 10,
        'maximum_stock' => 200,
    ]);

    $stock->update(['quantity' => 8]);

    Notification::assertSentTo($user, LowStockAlert::class);

    Notification::assertSentOnDemand(
        LowStockAlert::class,
        fn ($notification, $channels, $notifiable): bool => $notifiable->routes['mail'] === 'alerts@example.com'
    );
});

it('sends reorder point reached notification when stock drops below reorder point', function (): void {
    Notification::fake();

    $team = Team::factory()->create();
    $user = User::factory()->create(['team_id' => $team->id]);

    $supplier = Supplier::factory()->create(['team_id' => $team->id]);
    $product = Product::factory()->create([
        'team_id' => $team->id,
        'supplier_id' => $supplier->id,
        'reorder_point' => 20,
        'max_stock' => 100,
    ]);
    $warehouse = Warehouse::factory()->create(['team_id' => $team->id]);

    Stock::factory()->create([
        'team_id' => $team->id,
        'product_id' => $product->id,
        'warehouse_id' => $warehouse->id,
        'quantity' => 15,
        'minimum_stock' => 5,
        'maximum_stock' => 200,
    ]);

    Notification::assertSentTo($user, ReorderPointReached::class);
});

it('auto creates draft purchase order when auto_reorder_enabled', function (): void {
    Notification::fake();

    $team = Team::factory()->create();
    User::factory()->create(['team_id' => $team->id]);

    $team->setSetting('auto_reorder_enabled', true);

    $supplier = Supplier::factory()->create(['team_id' => $team->id]);
    $product = Product::factory()->create([
        'team_id' => $team->id,
        'supplier_id' => $supplier->id,
        'reorder_point' => 20,
        'max_stock' => 100,
    ]);
    $warehouse = Warehouse::factory()->create(['team_id' => $team->id]);

    Stock::factory()->create([
        'team_id' => $team->id,
        'product_id' => $product->id,
        'warehouse_id' => $warehouse->id,
        'quantity' => 15,
        'minimum_stock' => 5,
        'maximum_stock' => 200,
    ]);

    $order = Order::query()->where('supplier_id', $supplier->id)->first();

    expect($order)->not->toBeNull()
        ->and($order->status->value)->toBe('draft')
        ->and($order->type->value)->toBe('purchase')
        ->and($order->orderLines)->toHaveCount(1)
        ->and($order->orderLines->first()->product_id)->toBe($product->id);
});

it('does not auto create order when auto_reorder_enabled is false', function (): void {
    Notification::fake();

    $team = Team::factory()->create();
    User::factory()->create(['team_id' => $team->id]);

    $supplier = Supplier::factory()->create(['team_id' => $team->id]);
    $product = Product::factory()->create([
        'team_id' => $team->id,
        'supplier_id' => $supplier->id,
        'reorder_point' => 20,
        'max_stock' => 100,
    ]);
    $warehouse = Warehouse::factory()->create(['team_id' => $team->id]);

    Stock::factory()->create([
        'team_id' => $team->id,
        'product_id' => $product->id,
        'warehouse_id' => $warehouse->id,
        'quantity' => 15,
        'minimum_stock' => 5,
        'maximum_stock' => 200,
    ]);

    expect(Order::query()->where('supplier_id', $supplier->id)->exists())->toBeFalse();
});

it('does not duplicate purchase order if one already exists', function (): void {
    Notification::fake();

    $team = Team::factory()->create();
    User::factory()->create(['team_id' => $team->id]);

    $team->setSetting('auto_reorder_enabled', true);

    $supplier = Supplier::factory()->create(['team_id' => $team->id]);
    $product = Product::factory()->create([
        'team_id' => $team->id,
        'supplier_id' => $supplier->id,
        'reorder_point' => 20,
        'max_stock' => 100,
    ]);
    $warehouse = Warehouse::factory()->create(['team_id' => $team->id]);

    // Create existing open PO for this product
    $existingOrder = Order::factory()->purchaseOrder()->create([
        'team_id' => $team->id,
        'supplier_id' => $supplier->id,
        'status' => 'draft',
    ]);
    $existingOrder->orderLines()->create([
        'product_id' => $product->id,
        'quantity' => 50,
        'unit_price' => 10,
        'discount_percent' => 0,
    ]);

    Stock::factory()->create([
        'team_id' => $team->id,
        'product_id' => $product->id,
        'warehouse_id' => $warehouse->id,
        'quantity' => 15,
        'minimum_stock' => 5,
        'maximum_stock' => 200,
    ]);

    $orderCount = Order::query()->where('supplier_id', $supplier->id)->count();

    expect($orderCount)->toBe(1);
});
