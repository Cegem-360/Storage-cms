<?php

declare(strict_types=1);

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Filament\Resources\Orders\Pages\CreateOrder;
use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Pages\ViewOrder;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->team = $this->user->team;
    $this->actingAs($this->user);
});

it('can render the list page', function (): void {
    Livewire::test(ListOrders::class)
        ->assertOk();
});

it('can list orders', function (): void {
    $orders = Order::factory()
        ->count(3)
        ->recycle($this->team)
        ->create();

    Livewire::test(ListOrders::class)
        ->assertCanSeeTableRecords($orders);
});

it('can render the create page', function (): void {
    Livewire::test(CreateOrder::class)
        ->assertOk();
});

describe('Wizard create', function (): void {
    it('can create an order through wizard steps', function (): void {
        $product = Product::factory()->recycle($this->team)->create(['price' => 1000]);
        $orderNumber = 'ORD-TEST-'.fake()->unique()->numerify('######');

        Livewire::test(CreateOrder::class)
            ->fillForm([
                'order_number' => $orderNumber,
                'type' => OrderType::SALES->value,
                'status' => OrderStatus::DRAFT->value,
                'order_date' => now()->format('Y-m-d'),
                'shipping_address' => 'Test Address 123',
            ])
            ->set('data.orderLines', [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'unit_price' => '1000',
                    'discount_percent' => '0',
                    'tax_percent' => '27',
                ],
            ])
            ->goToWizardStep(3)
            ->call('create')
            ->assertHasNoFormErrors();

        assertDatabaseHas(Order::class, [
            'order_number' => $orderNumber,
            'type' => OrderType::SALES->value,
            'status' => OrderStatus::DRAFT->value,
        ]);

        $order = Order::query()->where('order_number', $orderNumber)->first();
        expect($order->orderLines)->toHaveCount(1)
            ->and($order->orderLines->first()->tax_percent)->toBe('27.00');
    });

    it('validates required fields on first wizard step', function (): void {
        Livewire::test(CreateOrder::class)
            ->fillForm([
                'order_number' => null,
                'type' => null,
                'order_date' => null,
            ])
            ->goToNextWizardStep()
            ->assertHasFormErrors([
                'order_number' => 'required',
                'type' => 'required',
                'order_date' => 'required',
            ]);
    });

    it('saves order line with tax_percent', function (): void {
        $product = Product::factory()->recycle($this->team)->create(['price' => 500]);
        $orderNumber = 'ORD-TAX-'.fake()->unique()->numerify('######');

        Livewire::test(CreateOrder::class)
            ->fillForm([
                'order_number' => $orderNumber,
                'type' => OrderType::PURCHASE->value,
                'status' => OrderStatus::DRAFT->value,
                'order_date' => now()->format('Y-m-d'),
            ])
            ->set('data.orderLines', [
                [
                    'product_id' => $product->id,
                    'quantity' => 10,
                    'unit_price' => '500',
                    'discount_percent' => '0',
                    'tax_percent' => '5',
                ],
            ])
            ->goToWizardStep(3)
            ->call('create')
            ->assertHasNoFormErrors();

        $order = Order::query()->where('order_number', $orderNumber)->first();
        $line = $order->orderLines->first();

        expect($line->tax_percent)->toBe('5.00')
            ->and($line->subtotal)->toBe(5000.0)
            ->and($line->tax_amount)->toBe(250.0)
            ->and($line->total_with_tax)->toBe(5250.0);
    });
});

it('can render the edit page', function (): void {
    $order = Order::factory()
        ->recycle($this->team)
        ->create();

    Livewire::test(EditOrder::class, ['record' => $order->getRouteKey()])
        ->assertOk();
});

it('can update an order', function (): void {
    $order = Order::factory()
        ->recycle($this->team)
        ->draft()
        ->create();

    $newOrderNumber = 'ORD-UPDATED-'.fake()->unique()->numerify('######');

    Livewire::test(EditOrder::class, ['record' => $order->getRouteKey()])
        ->fillForm([
            'order_number' => $newOrderNumber,
            'status' => OrderStatus::CONFIRMED->value,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Order::class, [
        'id' => $order->id,
        'order_number' => $newOrderNumber,
        'status' => OrderStatus::CONFIRMED->value,
    ]);
});

it('can render the view page', function (): void {
    $order = Order::factory()
        ->recycle($this->team)
        ->create();

    Livewire::test(ViewOrder::class, ['record' => $order->getRouteKey()])
        ->assertOk();
});

it('can delete an order', function (): void {
    $order = Order::factory()
        ->recycle($this->team)
        ->create();

    Livewire::test(EditOrder::class, ['record' => $order->getRouteKey()])
        ->callAction('delete');

    $this->assertSoftDeleted('orders', [
        'id' => $order->id,
    ]);
});

describe('Order totals with tax', function (): void {
    it('calculates net, tax, and gross totals correctly', function (): void {
        $order = Order::factory()->recycle($this->team)->create();
        $product = Product::factory()->recycle($this->team)->create();

        OrderLine::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 10,
            'unit_price' => 1000,
            'discount_percent' => 0,
            'tax_percent' => 27,
        ]);

        OrderLine::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 5,
            'unit_price' => 2000,
            'discount_percent' => 10,
            'tax_percent' => 5,
        ]);

        $order->load('orderLines');

        // Line 1: 10 * 1000 * 1.0 = 10000 net, 2700 tax, 12700 gross
        // Line 2: 5 * 2000 * 0.9 = 9000 net, 450 tax, 9450 gross
        expect($order->calculated_net_total)->toBe(19000.0)
            ->and($order->calculated_tax_total)->toBe(3150.0)
            ->and($order->calculated_total)->toBe(22150.0);
    });
});
