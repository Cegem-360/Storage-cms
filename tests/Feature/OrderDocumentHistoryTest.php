<?php

declare(strict_types=1);

use App\Filament\Resources\Orders\Pages\ViewOrder;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Receipt;
use App\Models\ReturnDelivery;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('shows receipts in document history', function (): void {
    $order = Order::factory()
        ->recycle($this->user->team)
        ->create();

    $receipt = Receipt::factory()
        ->recycle($this->user->team)
        ->create(['order_id' => $order->id]);

    Livewire::test(ViewOrder::class, ['record' => $order->getRouteKey()])
        ->assertOk()
        ->assertSee($receipt->receipt_number);
});

it('shows return deliveries in document history', function (): void {
    $order = Order::factory()
        ->recycle($this->user->team)
        ->create();

    $returnDelivery = ReturnDelivery::factory()
        ->recycle($this->user->team)
        ->create(['order_id' => $order->id]);

    Livewire::test(ViewOrder::class, ['record' => $order->getRouteKey()])
        ->assertOk()
        ->assertSee($returnDelivery->return_number);
});

it('shows invoices in document history', function (): void {
    $order = Order::factory()
        ->recycle($this->user->team)
        ->create();

    $invoice = Invoice::factory()
        ->recycle($this->user->team)
        ->create(['order_id' => $order->id]);

    Livewire::test(ViewOrder::class, ['record' => $order->getRouteKey()])
        ->assertOk()
        ->assertSee($invoice->invoice_number);
});

it('shows all document types together', function (): void {
    $order = Order::factory()
        ->recycle($this->user->team)
        ->create();

    $receipt = Receipt::factory()
        ->recycle($this->user->team)
        ->create(['order_id' => $order->id]);

    $returnDelivery = ReturnDelivery::factory()
        ->recycle($this->user->team)
        ->create(['order_id' => $order->id]);

    $invoice = Invoice::factory()
        ->recycle($this->user->team)
        ->create(['order_id' => $order->id]);

    Livewire::test(ViewOrder::class, ['record' => $order->getRouteKey()])
        ->assertOk()
        ->assertSee($receipt->receipt_number)
        ->assertSee($returnDelivery->return_number)
        ->assertSee($invoice->invoice_number);
});

it('order has returnDeliveries relationship', function (): void {
    $order = Order::factory()
        ->recycle($this->user->team)
        ->create();

    ReturnDelivery::factory()
        ->count(2)
        ->recycle($this->user->team)
        ->create(['order_id' => $order->id]);

    expect($order->returnDeliveries)->toHaveCount(2);
});

it('order has invoices relationship', function (): void {
    $order = Order::factory()
        ->recycle($this->user->team)
        ->create();

    Invoice::factory()
        ->count(3)
        ->recycle($this->user->team)
        ->create(['order_id' => $order->id]);

    expect($order->invoices)->toHaveCount(3);
});
