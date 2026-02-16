<?php

declare(strict_types=1);

use App\Filament\Resources\SupplierPrices\Pages\CreateSupplierPrice;
use App\Filament\Resources\SupplierPrices\Pages\EditSupplierPrice;
use App\Filament\Resources\SupplierPrices\Pages\ListSupplierPrices;
use App\Filament\Resources\SupplierPrices\Pages\ViewSupplierPrice;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\SupplierPrice;
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
});
