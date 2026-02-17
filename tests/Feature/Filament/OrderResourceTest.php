<?php

declare(strict_types=1);

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Filament\Resources\Orders\Pages\CreateOrder;
use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Pages\ViewOrder;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('can render the list page', function (): void {
    Livewire::test(ListOrders::class)
        ->assertOk();
});

it('can list orders', function (): void {
    $orders = Order::factory()
        ->count(3)
        ->recycle($this->user->team)
        ->create();

    Livewire::test(ListOrders::class)
        ->assertCanSeeTableRecords($orders);
});

it('can render the create page', function (): void {
    Livewire::test(CreateOrder::class)
        ->assertOk();
});

it('can create an order', function (): void {
    $orderNumber = 'ORD-TEST-'.fake()->unique()->numerify('######');

    Livewire::test(CreateOrder::class)
        ->fillForm([
            'order_number' => $orderNumber,
            'type' => OrderType::SALES->value,
            'status' => OrderStatus::DRAFT->value,
            'order_date' => now()->format('Y-m-d'),
            'orderLines' => [],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Order::class, [
        'order_number' => $orderNumber,
        'type' => OrderType::SALES->value,
        'status' => OrderStatus::DRAFT->value,
    ]);
});

it('can validate required fields on create', function (): void {
    Livewire::test(CreateOrder::class)
        ->fillForm([
            'order_number' => null,
            'type' => null,
            'order_date' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'order_number' => 'required',
            'type' => 'required',
            'order_date' => 'required',
        ]);
});

it('can render the edit page', function (): void {
    $order = Order::factory()
        ->recycle($this->user->team)
        ->create();

    Livewire::test(EditOrder::class, ['record' => $order->getRouteKey()])
        ->assertOk();
});

it('can update an order', function (): void {
    $order = Order::factory()
        ->recycle($this->user->team)
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
        ->recycle($this->user->team)
        ->create();

    Livewire::test(ViewOrder::class, ['record' => $order->getRouteKey()])
        ->assertOk();
});

it('can delete an order', function (): void {
    $order = Order::factory()
        ->recycle($this->user->team)
        ->create();

    Livewire::test(EditOrder::class, ['record' => $order->getRouteKey()])
        ->callAction('delete');

    $this->assertSoftDeleted('orders', [
        'id' => $order->id,
    ]);
});
