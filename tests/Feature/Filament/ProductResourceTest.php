<?php

declare(strict_types=1);

use App\Enums\ProductStatus;
use App\Enums\UnitType;
use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Resources\Products\Pages\ViewProduct;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe('Product Filament Resource', function (): void {
    it('can render the list page', function (): void {
        Livewire::test(ListProducts::class)
            ->assertOk();
    });

    it('can list products', function (): void {
        $products = Product::factory()
            ->count(3)
            ->recycle($this->user->team)
            ->create();

        Livewire::test(ListProducts::class)
            ->assertCanSeeTableRecords($products);
    });

    it('can render the create page', function (): void {
        Livewire::test(CreateProduct::class)
            ->assertOk();
    });

    it('can create a product', function (): void {
        $category = Category::factory()->recycle($this->user->team)->create();
        $supplier = Supplier::factory()->recycle($this->user->team)->create();

        Livewire::test(CreateProduct::class)
            ->fillForm([
                'sku' => 'TEST-SKU-001',
                'name' => 'Test Product',
                'category_id' => $category->id,
                'supplier_id' => $supplier->id,
                'status' => ProductStatus::ACTIVE,
                'unit_of_measure' => UnitType::PIECE,
                'price' => 100.00,
                'min_stock' => 5,
                'reorder_point' => 10,
                'max_stock' => 200,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('products', [
            'sku' => 'TEST-SKU-001',
            'name' => 'Test Product',
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'team_id' => $this->user->team_id,
        ]);
    });

    it('can render the edit page', function (): void {
        $product = Product::factory()->recycle($this->user->team)->create();

        Livewire::test(EditProduct::class, ['record' => $product->getRouteKey()])
            ->assertOk();
    });

    it('can edit a product', function (): void {
        $product = Product::factory()->recycle($this->user->team)->create();

        Livewire::test(EditProduct::class, ['record' => $product->getRouteKey()])
            ->fillForm([
                'name' => 'Updated Product Name',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($product->fresh()->name)->toBe('Updated Product Name');
    });

    it('can render the view page', function (): void {
        $product = Product::factory()->recycle($this->user->team)->create();

        Livewire::test(ViewProduct::class, ['record' => $product->getRouteKey()])
            ->assertOk();
    });

    it('validates required fields on create', function (): void {
        Livewire::test(CreateProduct::class)
            ->fillForm([
                'sku' => null,
                'name' => null,
                'category_id' => null,
                'supplier_id' => null,
            ])
            ->call('create')
            ->assertHasFormErrors([
                'sku' => 'required',
                'name' => 'required',
                'category_id' => 'required',
                'supplier_id' => 'required',
            ]);
    });

    it('validates unique sku within team on create', function (): void {
        $existingProduct = Product::factory()->recycle($this->user->team)->create([
            'sku' => 'DUPLICATE-SKU',
        ]);

        $category = Category::factory()->recycle($this->user->team)->create();
        $supplier = Supplier::factory()->recycle($this->user->team)->create();

        Livewire::test(CreateProduct::class)
            ->fillForm([
                'sku' => 'DUPLICATE-SKU',
                'name' => 'Another Product',
                'category_id' => $category->id,
                'supplier_id' => $supplier->id,
                'status' => ProductStatus::ACTIVE,
                'unit_of_measure' => UnitType::PIECE,
                'price' => 50.00,
                'min_stock' => 0,
                'reorder_point' => 0,
                'max_stock' => 0,
            ])
            ->call('create')
            ->assertHasFormErrors(['sku']);
    });

    it('allows same sku in different teams', function (): void {
        $otherUser = User::factory()->create();
        Product::factory()->recycle($otherUser->team)->create([
            'sku' => 'SHARED-SKU',
        ]);

        $category = Category::factory()->recycle($this->user->team)->create();
        $supplier = Supplier::factory()->recycle($this->user->team)->create();

        Livewire::test(CreateProduct::class)
            ->fillForm([
                'sku' => 'SHARED-SKU',
                'name' => 'My Product',
                'category_id' => $category->id,
                'supplier_id' => $supplier->id,
                'status' => ProductStatus::ACTIVE,
                'unit_of_measure' => UnitType::PIECE,
                'price' => 75.00,
                'min_stock' => 0,
                'reorder_point' => 0,
                'max_stock' => 0,
            ])
            ->call('create')
            ->assertHasNoFormErrors();
    });

    it('validates unique sku allows the same record on edit', function (): void {
        $product = Product::factory()->recycle($this->user->team)->create([
            'sku' => 'EXISTING-SKU',
        ]);

        Livewire::test(EditProduct::class, ['record' => $product->getRouteKey()])
            ->fillForm([
                'sku' => 'EXISTING-SKU',
                'name' => 'Updated Name',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($product->fresh()->name)->toBe('Updated Name');
    });

    it('can delete a product', function (): void {
        $product = Product::factory()->recycle($this->user->team)->create();

        Livewire::test(EditProduct::class, ['record' => $product->getRouteKey()])
            ->callAction('delete');

        $this->assertSoftDeleted('products', [
            'id' => $product->id,
        ]);
    });
});
