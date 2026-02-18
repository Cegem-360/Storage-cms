<?php

declare(strict_types=1);

use App\Filament\Resources\SupplierPrices\Pages\CreateSupplierPrice;
use App\Filament\Resources\SupplierPrices\Pages\EditSupplierPrice;
use App\Filament\Resources\SupplierPrices\Pages\ListSupplierPrices;
use App\Filament\Resources\SupplierPrices\Pages\ViewSupplierPrice;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\SupplierPrice;
use App\Models\SupplierPriceTier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe('SupplierPrice Filament Resource', function (): void {
    it('can render the list page', function (): void {
        Livewire::test(ListSupplierPrices::class)
            ->assertOk();
    });

    it('can list supplier prices', function (): void {
        $supplierPrices = SupplierPrice::factory()
            ->count(3)
            ->recycle($this->user->team)
            ->create();

        Livewire::test(ListSupplierPrices::class)
            ->assertCanSeeTableRecords($supplierPrices);
    });

    it('can render the create page', function (): void {
        Livewire::test(CreateSupplierPrice::class)
            ->assertOk();
    });

    it('can create a supplier price', function (): void {
        $supplier = Supplier::factory()->recycle($this->user->team)->create();
        $product = Product::factory()->recycle($this->user->team)->create();

        Livewire::test(CreateSupplierPrice::class)
            ->fillForm([
                'supplier_id' => $supplier->id,
                'product_id' => $product->id,
                'price' => 1500.50,
                'currency' => 'HUF',
                'minimum_order_quantity' => 10,
                'lead_time_days' => 5,
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('supplier_prices', [
            'supplier_id' => $supplier->id,
            'product_id' => $product->id,
            'currency' => 'HUF',
            'team_id' => $this->user->team_id,
        ]);
    });

    it('can render the edit page', function (): void {
        $supplierPrice = SupplierPrice::factory()
            ->recycle($this->user->team)
            ->create();

        Livewire::test(EditSupplierPrice::class, ['record' => $supplierPrice->getRouteKey()])
            ->assertOk();
    });

    it('can edit a supplier price', function (): void {
        $supplierPrice = SupplierPrice::factory()
            ->recycle($this->user->team)
            ->create();

        Livewire::test(EditSupplierPrice::class, ['record' => $supplierPrice->getRouteKey()])
            ->fillForm([
                'currency' => 'EUR',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($supplierPrice->fresh()->currency)->toBe('EUR');
    });

    it('can render the view page', function (): void {
        $supplierPrice = SupplierPrice::factory()
            ->recycle($this->user->team)
            ->create();

        Livewire::test(ViewSupplierPrice::class, ['record' => $supplierPrice->getRouteKey()])
            ->assertOk();
    });

    it('validates required fields on create', function (): void {
        Livewire::test(CreateSupplierPrice::class)
            ->fillForm([
                'supplier_id' => null,
                'product_id' => null,
                'price' => null,
                'currency' => null,
            ])
            ->call('create')
            ->assertHasFormErrors([
                'supplier_id' => 'required',
                'product_id' => 'required',
                'price' => 'required',
                'currency' => 'required',
            ]);
    });

    it('can delete a supplier price', function (): void {
        $supplierPrice = SupplierPrice::factory()
            ->recycle($this->user->team)
            ->create();

        Livewire::test(EditSupplierPrice::class, ['record' => $supplierPrice->getRouteKey()])
            ->callAction('delete');

        $this->assertModelMissing($supplierPrice);
    });
});

describe('SupplierPrice Tiers in Filament', function (): void {
    it('can create a supplier price with tiers', function (): void {
        $supplier = Supplier::factory()->recycle($this->user->team)->create();
        $product = Product::factory()->recycle($this->user->team)->create();

        Livewire::test(CreateSupplierPrice::class)
            ->fillForm([
                'supplier_id' => $supplier->id,
                'product_id' => $product->id,
                'price' => 500,
                'currency' => 'HUF',
                'minimum_order_quantity' => 1,
                'is_active' => true,
            ])
            ->set('data.tiers', [
                ['min_quantity' => 1, 'max_quantity' => 10, 'price' => 500],
                ['min_quantity' => 11, 'max_quantity' => 50, 'price' => 450],
                ['min_quantity' => 51, 'max_quantity' => null, 'price' => 400],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $supplierPrice = SupplierPrice::query()->latest()->first();

        expect($supplierPrice)->not->toBeNull()
            ->and($supplierPrice->tiers)->toHaveCount(3);
    });

    it('can view supplier price with tiers', function (): void {
        $supplierPrice = SupplierPrice::factory()
            ->recycle($this->user->team)
            ->create();

        SupplierPriceTier::create([
            'supplier_price_id' => $supplierPrice->id,
            'min_quantity' => 1,
            'max_quantity' => 10,
            'price' => 500.0000,
        ]);

        Livewire::test(ViewSupplierPrice::class, ['record' => $supplierPrice->getRouteKey()])
            ->assertOk();
    });

    it('can edit supplier price and add tiers', function (): void {
        $supplierPrice = SupplierPrice::factory()
            ->recycle($this->user->team)
            ->create();

        Livewire::test(EditSupplierPrice::class, ['record' => $supplierPrice->getRouteKey()])
            ->set('data.tiers', [
                ['min_quantity' => 10, 'max_quantity' => 50, 'price' => 400],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($supplierPrice->fresh()->tiers)->toHaveCount(1);
    });
});
