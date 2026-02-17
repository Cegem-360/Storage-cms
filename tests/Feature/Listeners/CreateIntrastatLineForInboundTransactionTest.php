<?php

declare(strict_types=1);

use App\Enums\IntrastatDirection;
use App\Enums\StockTransactionType;
use App\Events\InboundStockReceived;
use App\Listeners\CreateIntrastatLineForInboundTransaction;
use App\Models\IntrastatDeclaration;
use App\Models\IntrastatLine;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\Supplier;
use Illuminate\Support\Facades\Event;

use function Pest\Laravel\assertDatabaseHas;

beforeEach(function (): void {
    Event::fake([InboundStockReceived::class]);
});

it('creates intrastat line for inbound transaction with EU supplier', function (): void {
    $supplier = Supplier::factory()->create([
        'eu_tax_number' => 'BE123456789',
        'headquarters' => [
            'street' => 'Test Street',
            'city' => 'Brussels',
            'country' => 'BE',
        ],
    ]);

    $product = Product::factory()->create([
        'cn_code' => '99887766',
        'net_weight_kg' => 3.5,
    ]);

    $order = Order::factory()
        ->purchaseOrder()
        ->create(['supplier_id' => $supplier->id]);

    $transaction = StockTransaction::factory()->create([
        'type' => StockTransactionType::INBOUND,
        'product_id' => $product->id,
        'quantity' => 20,
        'unit_cost' => 50,
        'total_cost' => 1000,
        'reference_type' => Order::class,
        'reference_id' => $order->id,
    ]);

    $listener = resolve(CreateIntrastatLineForInboundTransaction::class);
    $listener->handle(new InboundStockReceived($transaction));

    expect(IntrastatDeclaration::query()->count())->toBe(1);
    expect(IntrastatLine::query()->count())->toBe(1);

    $declaration = IntrastatDeclaration::query()->first();
    expect($declaration->direction)->toBe(IntrastatDirection::ARRIVAL);

    assertDatabaseHas('intrastat_lines', [
        'intrastat_declaration_id' => $declaration->id,
        'order_id' => $order->id,
        'product_id' => $product->id,
        'supplier_id' => $supplier->id,
        'cn_code' => '99887766',
        'quantity' => 20,
        'invoice_value' => 1000,
        'country_of_origin' => 'BE',
    ]);
});

it('does not create intrastat line when supplier has no EU tax number', function (): void {
    $supplier = Supplier::factory()->create([
        'eu_tax_number' => null,
    ]);

    $product = Product::factory()->create(['cn_code' => '12345678']);

    $order = Order::factory()
        ->purchaseOrder()
        ->create(['supplier_id' => $supplier->id]);

    $transaction = StockTransaction::factory()->create([
        'type' => StockTransactionType::INBOUND,
        'product_id' => $product->id,
        'reference_type' => Order::class,
        'reference_id' => $order->id,
    ]);

    $listener = resolve(CreateIntrastatLineForInboundTransaction::class);
    $listener->handle(new InboundStockReceived($transaction));

    expect(IntrastatDeclaration::query()->count())->toBe(0);
    expect(IntrastatLine::query()->count())->toBe(0);
});

it('does not create intrastat line when product has no cn_code', function (): void {
    $supplier = Supplier::factory()->create([
        'eu_tax_number' => 'AT123456789',
        'headquarters' => ['country' => 'AT'],
    ]);

    $product = Product::factory()->create(['cn_code' => null]);

    $order = Order::factory()
        ->purchaseOrder()
        ->create(['supplier_id' => $supplier->id]);

    $transaction = StockTransaction::factory()->create([
        'type' => StockTransactionType::INBOUND,
        'product_id' => $product->id,
        'reference_type' => Order::class,
        'reference_id' => $order->id,
    ]);

    $listener = resolve(CreateIntrastatLineForInboundTransaction::class);
    $listener->handle(new InboundStockReceived($transaction));

    expect(IntrastatDeclaration::query()->count())->toBe(0);
    expect(IntrastatLine::query()->count())->toBe(0);
});

it('does not create intrastat line for outbound transaction type', function (): void {
    $transaction = StockTransaction::factory()->create([
        'type' => StockTransactionType::OUTBOUND,
    ]);

    $listener = resolve(CreateIntrastatLineForInboundTransaction::class);
    $listener->handle(new InboundStockReceived($transaction));

    expect(IntrastatDeclaration::query()->count())->toBe(0);
    expect(IntrastatLine::query()->count())->toBe(0);
});

it('does not create intrastat line when transaction has no order reference', function (): void {
    $transaction = StockTransaction::factory()->create([
        'type' => StockTransactionType::INBOUND,
        'reference_type' => null,
        'reference_id' => null,
    ]);

    $listener = resolve(CreateIntrastatLineForInboundTransaction::class);
    $listener->handle(new InboundStockReceived($transaction));

    expect(IntrastatDeclaration::query()->count())->toBe(0);
    expect(IntrastatLine::query()->count())->toBe(0);
});

it('calculates net mass using net_weight_kg field', function (): void {
    $supplier = Supplier::factory()->create([
        'eu_tax_number' => 'AT987654321',
        'headquarters' => ['country' => 'AT'],
    ]);

    $product = Product::factory()->create([
        'cn_code' => '12121212',
        'net_weight_kg' => 7.25,
    ]);

    $order = Order::factory()
        ->purchaseOrder()
        ->create(['supplier_id' => $supplier->id]);

    $transaction = StockTransaction::factory()->create([
        'type' => StockTransactionType::INBOUND,
        'product_id' => $product->id,
        'quantity' => 8,
        'unit_cost' => 25,
        'total_cost' => 200,
        'reference_type' => Order::class,
        'reference_id' => $order->id,
    ]);

    $listener = resolve(CreateIntrastatLineForInboundTransaction::class);
    $listener->handle(new InboundStockReceived($transaction));

    $line = IntrastatLine::query()->first();
    expect($line->net_mass)->toBe('58.000');
});

it('reuses existing declaration for same month', function (): void {
    $supplier = Supplier::factory()->create([
        'eu_tax_number' => 'CZ111222333',
        'headquarters' => ['country' => 'CZ'],
    ]);

    $product = Product::factory()->create(['cn_code' => '44444444']);

    $order1 = Order::factory()->purchaseOrder()->create(['supplier_id' => $supplier->id]);
    $order2 = Order::factory()->purchaseOrder()->create(['supplier_id' => $supplier->id]);

    $transaction1 = StockTransaction::factory()->create([
        'type' => StockTransactionType::INBOUND,
        'product_id' => $product->id,
        'reference_type' => Order::class,
        'reference_id' => $order1->id,
    ]);

    $listener = resolve(CreateIntrastatLineForInboundTransaction::class);
    $listener->handle(new InboundStockReceived($transaction1));

    expect(IntrastatDeclaration::query()->count())->toBe(1);

    $transaction2 = StockTransaction::factory()->create([
        'type' => StockTransactionType::INBOUND,
        'product_id' => $product->id,
        'reference_type' => Order::class,
        'reference_id' => $order2->id,
    ]);

    $listener->handle(new InboundStockReceived($transaction2));

    expect(IntrastatDeclaration::query()->count())->toBe(1);
    expect(IntrastatLine::query()->count())->toBe(2);
});
