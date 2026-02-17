<?php

declare(strict_types=1);

use App\Enums\StockStatus;
use App\Filament\Resources\Stocks\Pages\CreateStock;
use App\Filament\Resources\Stocks\Pages\EditStock;
use App\Filament\Resources\Stocks\Pages\ListStocks;
use App\Filament\Resources\Stocks\Pages\ViewStock;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe('Stock Filament Resource', function (): void {
    it('can render the list page', function (): void {
        Livewire::test(ListStocks::class)
            ->assertOk();
    });

    it('can list stocks', function (): void {
        $product = Product::factory()->recycle($this->user->team)->create();
        $warehouses = Warehouse::factory()
            ->count(3)
            ->recycle($this->user->team)
            ->create();

        $stocks = $warehouses->map(fn (Warehouse $warehouse) => Stock::factory()
            ->recycle($this->user->team)
            ->create([
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
            ])
        );

        Livewire::test(ListStocks::class)
            ->assertCanSeeTableRecords($stocks);
    });

    it('can render the create page', function (): void {
        Livewire::test(CreateStock::class)
            ->assertOk();
    });

    it('can create a stock', function (): void {
        $product = Product::factory()->recycle($this->user->team)->create();
        $warehouse = Warehouse::factory()->recycle($this->user->team)->create();

        Livewire::test(CreateStock::class)
            ->fillForm([
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
                'quantity' => 100,
                'reserved_quantity' => 0,
                'minimum_stock' => 10,
                'maximum_stock' => 500,
                'status' => StockStatus::IN_STOCK,
                'unit_cost' => 25.50,
                'total_value' => 2550.00,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('stocks', [
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'quantity' => 100,
            'team_id' => $this->user->team_id,
        ]);
    });

    it('can render the edit page', function (): void {
        $stock = Stock::factory()->recycle($this->user->team)->create();

        Livewire::test(EditStock::class, ['record' => $stock->getRouteKey()])
            ->assertOk();
    });

    it('can edit a stock', function (): void {
        $stock = Stock::factory()->recycle($this->user->team)->create();

        Livewire::test(EditStock::class, ['record' => $stock->getRouteKey()])
            ->fillForm([
                'quantity' => 250,
                'minimum_stock' => 15,
                'maximum_stock' => 600,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($stock->fresh())
            ->quantity->toBe(250)
            ->minimum_stock->toBe(15)
            ->maximum_stock->toBe(600);
    });

    it('can render the view page', function (): void {
        $stock = Stock::factory()->recycle($this->user->team)->create();

        Livewire::test(ViewStock::class, ['record' => $stock->getRouteKey()])
            ->assertOk();
    });

    it('validates required fields on create', function (): void {
        Livewire::test(CreateStock::class)
            ->fillForm([
                'product_id' => null,
                'warehouse_id' => null,
                'quantity' => null,
                'reserved_quantity' => null,
                'minimum_stock' => null,
                'maximum_stock' => null,
            ])
            ->call('create')
            ->assertHasFormErrors([
                'product_id' => 'required',
                'warehouse_id' => 'required',
                'quantity' => 'required',
                'reserved_quantity' => 'required',
                'minimum_stock' => 'required',
                'maximum_stock' => 'required',
            ]);
    });

    it('validates required fields on edit', function (): void {
        $stock = Stock::factory()->recycle($this->user->team)->create();

        Livewire::test(EditStock::class, ['record' => $stock->getRouteKey()])
            ->fillForm([
                'quantity' => null,
                'reserved_quantity' => null,
                'minimum_stock' => null,
                'maximum_stock' => null,
            ])
            ->call('save')
            ->assertHasFormErrors([
                'quantity' => 'required',
                'reserved_quantity' => 'required',
                'minimum_stock' => 'required',
                'maximum_stock' => 'required',
            ]);
    });

    it('can delete a stock', function (): void {
        $stock = Stock::factory()->recycle($this->user->team)->create();

        Livewire::test(EditStock::class, ['record' => $stock->getRouteKey()])
            ->callAction('delete');

        $this->assertSoftDeleted('stocks', [
            'id' => $stock->id,
        ]);
    });
});
