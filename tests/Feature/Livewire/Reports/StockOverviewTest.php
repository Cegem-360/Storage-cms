<?php

declare(strict_types=1);

use App\Livewire\Pages\Reports\StockOverview;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use App\Models\Warehouse;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('renders the stock overview page', function (): void {
    $this->get(route('dashboard.stock-overview'))
        ->assertSuccessful();
});

it('renders the stock overview livewire component', function (): void {
    Livewire::test(StockOverview::class)
        ->assertOk();
});

it('displays stock data grouped by warehouse', function (): void {
    $team = $this->user->team;
    $warehouse = Warehouse::factory()->recycle($team)->create();
    $product = Product::factory()->recycle($team)->create();

    Stock::factory()->recycle($team)->recycle($warehouse)->create([
        'product_id' => $product->id,
        'quantity' => 100,
    ]);

    Livewire::test(StockOverview::class)
        ->assertOk()
        ->assertSee($product->sku)
        ->assertSee($warehouse->name);
});

it('requires authentication', function (): void {
    auth()->logout();

    $this->get(route('dashboard.stock-overview'))
        ->assertRedirect();
});
