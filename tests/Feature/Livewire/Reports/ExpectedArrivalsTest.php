<?php

declare(strict_types=1);

use App\Livewire\Pages\Reports\ExpectedArrivals;
use App\Models\Order;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('renders the expected arrivals page', function (): void {
    $this->get(route('dashboard.expected-arrivals'))
        ->assertSuccessful();
});

it('renders the expected arrivals livewire component', function (): void {
    Livewire::test(ExpectedArrivals::class)
        ->assertOk();
});

it('displays confirmed purchase orders with delivery dates', function (): void {
    $team = $this->user->team;

    $order = Order::factory()
        ->purchaseOrder()
        ->confirmed()
        ->recycle($team)
        ->create(['delivery_date' => now()->addDays(5)]);

    Livewire::test(ExpectedArrivals::class)
        ->assertOk()
        ->assertSee($order->order_number);
});

it('does not display draft orders', function (): void {
    $team = $this->user->team;

    $order = Order::factory()
        ->purchaseOrder()
        ->draft()
        ->recycle($team)
        ->create(['delivery_date' => now()->addDays(5)]);

    Livewire::test(ExpectedArrivals::class)
        ->assertOk()
        ->assertDontSee($order->order_number);
});

it('does not display orders without delivery date', function (): void {
    $team = $this->user->team;

    $order = Order::factory()
        ->purchaseOrder()
        ->confirmed()
        ->recycle($team)
        ->create(['delivery_date' => null]);

    Livewire::test(ExpectedArrivals::class)
        ->assertOk()
        ->assertDontSee($order->order_number);
});

it('requires authentication', function (): void {
    auth()->logout();

    $this->get(route('dashboard.expected-arrivals'))
        ->assertRedirect();
});
