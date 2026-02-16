<?php

declare(strict_types=1);

use App\Enums\ProductStatus;
use App\Enums\UnitType;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

describe('GET /api/v1/products', function (): void {
    it('lists products', function (): void {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $products = Product::factory()->count(3)->recycle($user->team)->create();

        $response = $this->getJson('/api/v1/products');

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'sku', 'name', 'price', 'status', 'category', 'supplier'],
                ],
                'links',
                'meta',
            ]);
    });

    it('filters products by status', function (): void {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        Product::factory()->active()->count(2)->recycle($user->team)->create();
        Product::factory()->discontinued()->recycle($user->team)->create();

        $response = $this->getJson('/api/v1/products?status=active');

        $response->assertOk()
            ->assertJsonCount(2, 'data');

        expect(collect($response->json('data'))->pluck('status'))->each(
            fn ($status) => $status->toBe(ProductStatus::ACTIVE->value)
        );
    });

    it('requires authentication', function (): void {
        $response = $this->getJson('/api/v1/products');

        $response->assertUnauthorized();
    });
});

describe('GET /api/v1/products/{id}', function (): void {
    it('shows a single product', function (): void {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $product = Product::factory()->recycle($user->team)->create();

        $response = $this->getJson("/api/v1/products/{$product->id}");

        $response->assertOk()
            ->assertJsonPath('data.id', $product->id)
            ->assertJsonPath('data.sku', $product->sku)
            ->assertJsonPath('data.name', $product->name)
            ->assertJsonStructure([
                'data' => [
                    'id', 'sku', 'name', 'description', 'barcode',
                    'unit_of_measure', 'weight', 'dimensions',
                    'min_stock', 'max_stock', 'reorder_point', 'price', 'status',
                    'category', 'supplier', 'created_at', 'updated_at',
                ],
            ]);
    });
});

describe('POST /api/v1/products', function (): void {
    it('creates a product', function (): void {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $category = Category::factory()->recycle($user->team)->create();
        $supplier = Supplier::factory()->recycle($user->team)->create();

        $data = [
            'sku' => 'SKU-TEST-001',
            'name' => 'Test Product',
            'description' => 'A test product description',
            'barcode' => '1234567890123',
            'unit_of_measure' => UnitType::PIECE->value,
            'weight' => 1.50,
            'dimensions' => ['length' => 10, 'width' => 5, 'height' => 3, 'unit' => 'cm'],
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'min_stock' => 10,
            'max_stock' => 100,
            'reorder_point' => 20,
            'price' => 29.99,
            'status' => ProductStatus::ACTIVE->value,
        ];

        $response = $this->postJson('/api/v1/products', $data);

        $response->assertCreated()
            ->assertJsonPath('data.sku', 'SKU-TEST-001')
            ->assertJsonPath('data.name', 'Test Product');

        $this->assertDatabaseHas('products', [
            'sku' => 'SKU-TEST-001',
            'name' => 'Test Product',
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
        ]);
    });

    it('validates required fields on create', function (): void {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/v1/products', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['sku', 'name', 'unit_of_measure', 'category_id', 'supplier_id', 'price']);
    });
});

describe('PUT /api/v1/products/{id}', function (): void {
    it('updates a product', function (): void {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $product = Product::factory()->recycle($user->team)->create();

        $response = $this->putJson("/api/v1/products/{$product->id}", [
            'name' => 'Updated Product Name',
            'price' => 49.99,
        ]);

        $response->assertOk()
            ->assertJsonPath('data.name', 'Updated Product Name')
            ->assertJsonPath('data.price', '49.99');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product Name',
        ]);
    });
});

describe('DELETE /api/v1/products/{id}', function (): void {
    it('deletes a product', function (): void {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $product = Product::factory()->recycle($user->team)->create();

        $response = $this->deleteJson("/api/v1/products/{$product->id}");

        $response->assertNoContent();

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    });
});
