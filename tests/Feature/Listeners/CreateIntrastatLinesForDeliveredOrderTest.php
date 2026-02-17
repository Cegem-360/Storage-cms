<?php

declare(strict_types=1);

use App\Enums\IntrastatDirection;
use App\Enums\IntrastatStatus;
use App\Enums\OrderStatus;
use App\Events\OrderDelivered;
use App\Listeners\CreateIntrastatLinesForDeliveredOrder;
use App\Models\IntrastatDeclaration;
use App\Models\IntrastatLine;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Product;
use App\Models\Supplier;

use function Pest\Laravel\assertDatabaseHas;

it('creates intrastat arrival lines for purchase order with EU supplier', function (): void {
    $supplier = Supplier::factory()->create([
        'eu_tax_number' => 'DE123456789',
        'headquarters' => [
            'street' => 'Test Street 1',
            'city' => 'Berlin',
            'country' => 'DE',
        ],
    ]);

    $product = Product::factory()->create([
        'cn_code' => '12345678',
        'net_weight_kg' => 5.5,
    ]);

    $order = Order::factory()
        ->purchaseOrder()
        ->create([
            'supplier_id' => $supplier->id,
            'delivery_date' => now(),
            'status' => OrderStatus::DELIVERED,
        ]);

    OrderLine::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 10,
        'unit_price' => 100,
    ]);

    $listener = resolve(CreateIntrastatLinesForDeliveredOrder::class);
    $listener->handle(new OrderDelivered($order));

    expect(IntrastatDeclaration::query()->count())->toBe(1);

    $declaration = IntrastatDeclaration::query()->first();
    expect($declaration->direction)->toBe(IntrastatDirection::ARRIVAL)
        ->and($declaration->status)->toBe(IntrastatStatus::DRAFT);

    assertDatabaseHas('intrastat_lines', [
        'intrastat_declaration_id' => $declaration->id,
        'order_id' => $order->id,
        'product_id' => $product->id,
        'supplier_id' => $supplier->id,
        'cn_code' => '12345678',
        'quantity' => 10,
        'country_of_origin' => 'DE',
    ]);
});

it('does not create intrastat lines when supplier has no EU tax number', function (): void {
    $supplier = Supplier::factory()->create([
        'eu_tax_number' => null,
    ]);

    $order = Order::factory()
        ->purchaseOrder()
        ->create([
            'supplier_id' => $supplier->id,
            'status' => OrderStatus::DELIVERED,
        ]);

    OrderLine::factory()->create(['order_id' => $order->id]);

    $listener = resolve(CreateIntrastatLinesForDeliveredOrder::class);
    $listener->handle(new OrderDelivered($order));

    expect(IntrastatDeclaration::query()->count())->toBe(0);
    expect(IntrastatLine::query()->count())->toBe(0);
});

it('creates intrastat dispatch lines for sales order with EU customer', function (): void {
    $order = Order::factory()
        ->salesOrder()
        ->create([
            'shipping_address' => [
                'street' => 'Test Street',
                'city' => 'Paris',
                'country' => 'FR',
            ],
            'status' => OrderStatus::DELIVERED,
        ]);

    $product = Product::factory()->create([
        'cn_code' => '87654321',
        'net_weight_kg' => 2.5,
    ]);

    OrderLine::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 5,
        'unit_price' => 200,
    ]);

    $listener = resolve(CreateIntrastatLinesForDeliveredOrder::class);
    $listener->handle(new OrderDelivered($order));

    expect(IntrastatDeclaration::query()->count())->toBe(1);

    $declaration = IntrastatDeclaration::query()->first();
    expect($declaration->direction)->toBe(IntrastatDirection::DISPATCH);

    assertDatabaseHas('intrastat_lines', [
        'intrastat_declaration_id' => $declaration->id,
        'order_id' => $order->id,
        'product_id' => $product->id,
        'cn_code' => '87654321',
        'quantity' => 5,
        'country_of_destination' => 'FR',
    ]);
});

it('does not create intrastat lines for non-EU customer', function (): void {
    $order = Order::factory()
        ->salesOrder()
        ->create([
            'shipping_address' => [
                'street' => 'Test Street',
                'city' => 'New York',
                'country' => 'US',
            ],
            'status' => OrderStatus::DELIVERED,
        ]);

    OrderLine::factory()->create(['order_id' => $order->id]);

    $listener = resolve(CreateIntrastatLinesForDeliveredOrder::class);
    $listener->handle(new OrderDelivered($order));

    expect(IntrastatDeclaration::query()->count())->toBe(0);
    expect(IntrastatLine::query()->count())->toBe(0);
});

it('creates declaration with correct month and year from delivery date', function (): void {
    $supplier = Supplier::factory()->create([
        'eu_tax_number' => 'IT123456789',
        'headquarters' => ['country' => 'IT'],
    ]);

    $deliveryDate = now()->setDate(2025, 3, 15);

    $order = Order::factory()
        ->purchaseOrder()
        ->create([
            'supplier_id' => $supplier->id,
            'delivery_date' => $deliveryDate,
            'status' => OrderStatus::DELIVERED,
        ]);

    OrderLine::factory()->create(['order_id' => $order->id]);

    $listener = resolve(CreateIntrastatLinesForDeliveredOrder::class);
    $listener->handle(new OrderDelivered($order));

    $declaration = IntrastatDeclaration::query()->first();
    expect($declaration->reference_year)->toBe(2025)
        ->and($declaration->reference_month)->toBe(3)
        ->and($declaration->declaration_number)->toBe('arrival-2025-03');
});

it('reuses existing declaration for same month and direction', function (): void {
    $supplier = Supplier::factory()->create([
        'eu_tax_number' => 'ES123456789',
        'headquarters' => ['country' => 'ES'],
    ]);

    $product = Product::factory()->create(['cn_code' => '12345678']);

    $order1 = Order::factory()
        ->purchaseOrder()
        ->create([
            'supplier_id' => $supplier->id,
            'delivery_date' => now(),
            'status' => OrderStatus::DELIVERED,
        ]);

    OrderLine::factory()->create([
        'order_id' => $order1->id,
        'product_id' => $product->id,
    ]);

    $listener = resolve(CreateIntrastatLinesForDeliveredOrder::class);
    $listener->handle(new OrderDelivered($order1));

    expect(IntrastatDeclaration::query()->count())->toBe(1);

    $order2 = Order::factory()
        ->purchaseOrder()
        ->create([
            'supplier_id' => $supplier->id,
            'delivery_date' => now(),
            'status' => OrderStatus::DELIVERED,
        ]);

    OrderLine::factory()->create([
        'order_id' => $order2->id,
        'product_id' => $product->id,
    ]);

    $listener->handle(new OrderDelivered($order2));

    expect(IntrastatDeclaration::query()->count())->toBe(1);
    expect(IntrastatLine::query()->count())->toBe(2);
});

it('calculates totals correctly when lines are added', function (): void {
    $supplier = Supplier::factory()->create([
        'eu_tax_number' => 'NL123456789',
        'headquarters' => ['country' => 'NL'],
    ]);

    $order = Order::factory()
        ->purchaseOrder()
        ->create([
            'supplier_id' => $supplier->id,
            'status' => OrderStatus::DELIVERED,
        ]);

    $product = Product::factory()->create([
        'net_weight_kg' => 10,
        'cn_code' => '11111111',
    ]);

    OrderLine::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 5,
        'unit_price' => 100,
    ]);

    $listener = resolve(CreateIntrastatLinesForDeliveredOrder::class);
    $listener->handle(new OrderDelivered($order));

    $declaration = IntrastatDeclaration::query()->first();
    expect($declaration->total_invoice_value)->toBe('500.00')
        ->and($declaration->total_net_mass)->toBe('50.000');
});

it('skips order lines without cn_code on the product', function (): void {
    $supplier = Supplier::factory()->create([
        'eu_tax_number' => 'DE111111111',
        'headquarters' => ['country' => 'DE'],
    ]);

    $productWithCode = Product::factory()->create(['cn_code' => '12345678']);
    $productWithoutCode = Product::factory()->create(['cn_code' => null]);

    $order = Order::factory()
        ->purchaseOrder()
        ->create([
            'supplier_id' => $supplier->id,
            'status' => OrderStatus::DELIVERED,
        ]);

    OrderLine::factory()->create([
        'order_id' => $order->id,
        'product_id' => $productWithCode->id,
    ]);

    OrderLine::factory()->create([
        'order_id' => $order->id,
        'product_id' => $productWithoutCode->id,
    ]);

    $listener = resolve(CreateIntrastatLinesForDeliveredOrder::class);
    $listener->handle(new OrderDelivered($order));

    expect(IntrastatLine::query()->count())->toBe(1);
});
