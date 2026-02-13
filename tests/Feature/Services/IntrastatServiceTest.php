<?php

declare(strict_types=1);

use App\Enums\CountryCode;
use App\Enums\IntrastatDirection;
use App\Enums\IntrastatStatus;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Customer;
use App\Models\IntrastatDeclaration;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Product;
use App\Models\Supplier;
use App\Services\IntrastatService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->service = new IntrastatService();
});

it('generates declaration for period with arrival direction', function (): void {
    $supplier = Supplier::factory()->create([
        'country_code' => CountryCode::DE,
        'is_eu_member' => true,
    ]);

    $product = Product::factory()->create([
        'cn_code' => '12345678',
        'net_weight_kg' => 1.5,
    ]);

    $order = Order::factory()->create([
        'type' => OrderType::PURCHASE,
        'status' => OrderStatus::COMPLETED,
        'supplier_id' => $supplier->id,
        'order_date' => '2025-01-15',
    ]);

    OrderLine::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 10,
        'unit_price' => 10000,
    ]);

    $declaration = $this->service->generateDeclarationForPeriod(2025, 1, IntrastatDirection::ARRIVAL);

    expect($declaration)->toBeInstanceOf(IntrastatDeclaration::class)
        ->and($declaration->direction)->toBe(IntrastatDirection::ARRIVAL)
        ->and($declaration->reference_year)->toBe(2025)
        ->and($declaration->reference_month)->toBe(1)
        ->and($declaration->status)->toBe(IntrastatStatus::DRAFT)
        ->and($declaration->intrastatLines()->count())->toBeGreaterThan(0);
});

it('generates declaration for period with dispatch direction', function (): void {
    $customer = Customer::factory()->create();

    $product = Product::factory()->create([
        'cn_code' => '87654321',
        'net_weight_kg' => 2.5,
    ]);

    $order = Order::factory()->create([
        'type' => OrderType::SALE,
        'status' => OrderStatus::COMPLETED,
        'customer_id' => $customer->id,
        'order_date' => '2025-02-10',
    ]);

    OrderLine::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 5,
        'unit_price' => 10000,
    ]);

    $declaration = $this->service->generateDeclarationForPeriod(2025, 2, IntrastatDirection::DISPATCH);

    expect($declaration)->toBeInstanceOf(IntrastatDeclaration::class)
        ->and($declaration->direction)->toBe(IntrastatDirection::DISPATCH)
        ->and($declaration->reference_year)->toBe(2025)
        ->and($declaration->reference_month)->toBe(2)
        ->and($declaration->status)->toBe(IntrastatStatus::DRAFT);
});

it('generates declaration number correctly', function (): void {
    $declaration = $this->service->generateDeclarationForPeriod(2025, 3, IntrastatDirection::ARRIVAL);

    expect($declaration->declaration_number)->toContain('INTRASTAT-202503-A');
});

it('calculates totals after generating lines', function (): void {
    $supplier = Supplier::factory()->create([
        'country_code' => CountryCode::FR,
        'is_eu_member' => true,
    ]);

    $product = Product::factory()->create([
        'cn_code' => '11223344',
        'net_weight_kg' => 3.0,
    ]);

    $order = Order::factory()->create([
        'type' => OrderType::PURCHASE,
        'status' => OrderStatus::COMPLETED,
        'supplier_id' => $supplier->id,
        'order_date' => '2025-01-20',
    ]);

    OrderLine::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 20,
        'unit_price' => 10000,
    ]);

    $declaration = $this->service->generateDeclarationForPeriod(2025, 1, IntrastatDirection::ARRIVAL);

    expect($declaration->total_invoice_value)->toBeGreaterThan(0)
        ->and($declaration->total_statistical_value)->toBeGreaterThan(0)
        ->and($declaration->total_net_mass)->toBeGreaterThan(0);
});

it('skips orders without CN code when generating lines', function (): void {
    $supplier = Supplier::factory()->create([
        'country_code' => CountryCode::DE,
        'is_eu_member' => true,
    ]);

    $product = Product::factory()->create([
        'cn_code' => null,
    ]);

    $order = Order::factory()->create([
        'type' => OrderType::PURCHASE,
        'status' => OrderStatus::COMPLETED,
        'supplier_id' => $supplier->id,
        'order_date' => '2025-01-15',
    ]);

    OrderLine::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 10,
        'unit_price' => 10000,
    ]);

    $declaration = $this->service->generateDeclarationForPeriod(2025, 1, IntrastatDirection::ARRIVAL);

    expect($declaration->intrastatLines()->count())->toBe(0);
});

it('skips non-EU suppliers when generating arrival lines', function (): void {
    $supplier = Supplier::factory()->create([
        'country_code' => CountryCode::US,
        'is_eu_member' => false,
    ]);

    $product = Product::factory()->create([
        'cn_code' => '12345678',
    ]);

    $order = Order::factory()->create([
        'type' => OrderType::PURCHASE,
        'status' => OrderStatus::COMPLETED,
        'supplier_id' => $supplier->id,
        'order_date' => '2025-01-15',
    ]);

    OrderLine::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 10,
        'unit_price' => 10000,
    ]);

    $declaration = $this->service->generateDeclarationForPeriod(2025, 1, IntrastatDirection::ARRIVAL);

    expect($declaration->intrastatLines()->count())->toBe(0);
});

it('skips Hungarian suppliers when generating arrival lines', function (): void {
    $supplier = Supplier::factory()->create([
        'country_code' => CountryCode::HU,
        'is_eu_member' => true,
    ]);

    $product = Product::factory()->create([
        'cn_code' => '12345678',
    ]);

    $order = Order::factory()->create([
        'type' => OrderType::PURCHASE,
        'status' => OrderStatus::COMPLETED,
        'supplier_id' => $supplier->id,
        'order_date' => '2025-01-15',
    ]);

    OrderLine::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 10,
        'unit_price' => 10000,
    ]);

    $declaration = $this->service->generateDeclarationForPeriod(2025, 1, IntrastatDirection::ARRIVAL);

    expect($declaration->intrastatLines()->count())->toBe(0);
});

it('uses transaction within generateDeclarationForPeriod', function (): void {
    $supplier = Supplier::factory()->create([
        'country_code' => CountryCode::DE,
        'is_eu_member' => true,
    ]);

    $product = Product::factory()->create([
        'cn_code' => '12345678',
        'net_weight_kg' => 1.5,
    ]);

    $order = Order::factory()->create([
        'type' => OrderType::PURCHASE,
        'status' => OrderStatus::COMPLETED,
        'supplier_id' => $supplier->id,
        'order_date' => '2025-01-15',
    ]);

    OrderLine::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 10,
        'unit_price' => 10000,
    ]);

    $initialDeclarationCount = IntrastatDeclaration::query()->count();

    $declaration = $this->service->generateDeclarationForPeriod(2025, 1, IntrastatDirection::ARRIVAL);

    expect(IntrastatDeclaration::query()->count())->toBe($initialDeclarationCount + 1)
        ->and($declaration->exists)->toBeTrue();
});
