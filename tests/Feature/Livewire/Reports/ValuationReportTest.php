<?php

declare(strict_types=1);

use App\Livewire\Pages\Reports\ValuationReport;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use App\Models\Warehouse;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('renders the valuation report page', function (): void {
    $this->get(route('dashboard.valuation-report'))
        ->assertSuccessful();
});

it('renders the valuation report livewire component', function (): void {
    Livewire::test(ValuationReport::class)
        ->assertOk();
});

it('displays stock valuations with unit cost', function (): void {
    $team = $this->user->team;
    $warehouse = Warehouse::factory()->recycle($team)->create();
    $product = Product::factory()->recycle($team)->create(['price' => 100.00]);

    Stock::factory()->recycle($team)->recycle($warehouse)->create([
        'product_id' => $product->id,
        'quantity' => 10,
        'unit_cost' => 50.0000,
    ]);

    Livewire::test(ValuationReport::class)
        ->assertOk()
        ->assertSee($product->sku);
});

it('falls back to product price when unit cost is zero', function (): void {
    $team = $this->user->team;
    $warehouse = Warehouse::factory()->recycle($team)->create();
    $product = Product::factory()->recycle($team)->create([
        'price' => 200.00,
        'standard_cost' => null,
    ]);

    Stock::factory()->recycle($team)->recycle($warehouse)->create([
        'product_id' => $product->id,
        'quantity' => 5,
        'unit_cost' => 0,
    ]);

    Livewire::test(ValuationReport::class)
        ->assertOk()
        ->assertSee($product->sku);
});

it('requires authentication', function (): void {
    auth()->logout();

    $this->get(route('dashboard.valuation-report'))
        ->assertRedirect();
});
