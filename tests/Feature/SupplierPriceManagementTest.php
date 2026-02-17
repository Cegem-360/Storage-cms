<?php

declare(strict_types=1);

use App\Models\Product;
use App\Models\Supplier;
use App\Models\SupplierPrice;
use App\Models\SupplierPriceTier;

uses()->group('price-management');

test('product can have multiple supplier prices', function (): void {
    $product = Product::factory()->create(['name' => 'Csavar']);
    $supplierA = Supplier::factory()->create(['company_name' => 'A Szállító']);
    $supplierB = Supplier::factory()->create(['company_name' => 'B Szállító']);

    SupplierPrice::factory()->create([
        'product_id' => $product->id,
        'supplier_id' => $supplierA->id,
        'price' => 3.0000,
    ]);

    SupplierPrice::factory()->create([
        'product_id' => $product->id,
        'supplier_id' => $supplierB->id,
        'price' => 2.5000,
    ]);

    expect($product->supplierPrices)->toHaveCount(2);
});

test('can retrieve active supplier prices for product', function (): void {
    $product = Product::factory()->create();
    $supplier = Supplier::factory()->create();

    SupplierPrice::factory()->active()->create([
        'product_id' => $product->id,
        'supplier_id' => $supplier->id,
        'price' => 100.0000,
    ]);

    SupplierPrice::factory()->inactive()->create([
        'product_id' => $product->id,
        'supplier_id' => $supplier->id,
        'price' => 200.0000,
        'valid_from' => now()->subDays(60),
    ]);

    $activePrices = $product->getActiveSupplierPrices();

    expect($activePrices)->toHaveCount(1)
        ->first()->price->toBe('100.0000');
});

test('can find best price for product', function (): void {
    $product = Product::factory()->create();
    $supplierA = Supplier::factory()->create();
    $supplierB = Supplier::factory()->create();
    $supplierC = Supplier::factory()->create();

    SupplierPrice::factory()->active()->create([
        'product_id' => $product->id,
        'supplier_id' => $supplierA->id,
        'price' => 150.0000,
    ]);

    SupplierPrice::factory()->active()->create([
        'product_id' => $product->id,
        'supplier_id' => $supplierB->id,
        'price' => 120.5000,
    ]);

    SupplierPrice::factory()->active()->create([
        'product_id' => $product->id,
        'supplier_id' => $supplierC->id,
        'price' => 180.0000,
    ]);

    $bestPrice = $product->getBestPrice();

    expect($bestPrice)
        ->not->toBeNull()
        ->price->toBe('120.5000')
        ->and($bestPrice->supplier_id)->toBe($supplierB->id);
});

test('expired prices are not included in active prices', function (): void {
    $product = Product::factory()->create();
    $supplier = Supplier::factory()->create();

    SupplierPrice::factory()->expired()->create([
        'product_id' => $product->id,
        'supplier_id' => $supplier->id,
        'price' => 50.0000,
    ]);

    $activePrices = $product->getActiveSupplierPrices();

    expect($activePrices)->toHaveCount(0);
});

test('can check if price is currently valid', function (): void {
    $activePrice = SupplierPrice::factory()->active()->create();
    $expiredPrice = SupplierPrice::factory()->expired()->create();
    $inactivePrice = SupplierPrice::factory()->inactive()->create();

    expect($activePrice->isCurrentlyValid())->toBeTrue()
        ->and($expiredPrice->isCurrentlyValid())->toBeFalse()
        ->and($inactivePrice->isCurrentlyValid())->toBeFalse();
});

test('price multiplied by quantity gives correct total', function (): void {
    $price = SupplierPrice::factory()->create([
        'price' => 25.5000,
    ]);

    $totalPrice = $price->price * 10;

    expect((float) $totalPrice)->toBe(255.0);
});

test('product with different supplier prices shows correct values', function (): void {
    $product = Product::factory()->create(['name' => 'Csavar M8']);
    $supplierA = Supplier::factory()->create(['company_name' => 'A Szállító']);
    $supplierB = Supplier::factory()->create(['company_name' => 'B Szállító']);

    $priceA = SupplierPrice::factory()->create([
        'product_id' => $product->id,
        'supplier_id' => $supplierA->id,
        'price' => 3.0000,
    ]);

    $priceB = SupplierPrice::factory()->create([
        'product_id' => $product->id,
        'supplier_id' => $supplierB->id,
        'price' => 2.5000,
    ]);

    expect($priceA->supplier->company_name)->toBe('A Szállító')
        ->and((float) $priceA->price)->toBe(3.0)
        ->and($priceB->supplier->company_name)->toBe('B Szállító')
        ->and((float) $priceB->price)->toBe(2.5);
});

describe('Quantity Discount Tiers', function (): void {
    it('can create tiers for a supplier price', function (): void {
        $supplierPrice = SupplierPrice::factory()->create(['price' => 500.0000]);

        SupplierPriceTier::create([
            'supplier_price_id' => $supplierPrice->id,
            'min_quantity' => 1,
            'max_quantity' => 10,
            'price' => 500.0000,
        ]);

        SupplierPriceTier::create([
            'supplier_price_id' => $supplierPrice->id,
            'min_quantity' => 11,
            'max_quantity' => 50,
            'price' => 450.0000,
        ]);

        SupplierPriceTier::create([
            'supplier_price_id' => $supplierPrice->id,
            'min_quantity' => 51,
            'max_quantity' => null,
            'price' => 400.0000,
        ]);

        expect($supplierPrice->tiers)->toHaveCount(3);
    });

    it('returns correct tier price for quantity', function (): void {
        $supplierPrice = SupplierPrice::factory()->create(['price' => 500.0000]);

        SupplierPriceTier::create([
            'supplier_price_id' => $supplierPrice->id,
            'min_quantity' => 1,
            'max_quantity' => 10,
            'price' => 500.0000,
        ]);

        SupplierPriceTier::create([
            'supplier_price_id' => $supplierPrice->id,
            'min_quantity' => 11,
            'max_quantity' => 50,
            'price' => 450.0000,
        ]);

        SupplierPriceTier::create([
            'supplier_price_id' => $supplierPrice->id,
            'min_quantity' => 51,
            'max_quantity' => null,
            'price' => 400.0000,
        ]);

        expect($supplierPrice->getPriceForQuantity(5))->toBe('500.0000')
            ->and($supplierPrice->getPriceForQuantity(10))->toBe('500.0000')
            ->and($supplierPrice->getPriceForQuantity(11))->toBe('450.0000')
            ->and($supplierPrice->getPriceForQuantity(25))->toBe('450.0000')
            ->and($supplierPrice->getPriceForQuantity(51))->toBe('400.0000')
            ->and($supplierPrice->getPriceForQuantity(100))->toBe('400.0000');
    });

    it('returns base price when no tiers exist', function (): void {
        $supplierPrice = SupplierPrice::factory()->create(['price' => 500.0000]);

        expect($supplierPrice->getPriceForQuantity(10))->toBe('500.0000');
    });

    it('returns base price when quantity does not match any tier', function (): void {
        $supplierPrice = SupplierPrice::factory()->create(['price' => 500.0000]);

        SupplierPriceTier::create([
            'supplier_price_id' => $supplierPrice->id,
            'min_quantity' => 10,
            'max_quantity' => 50,
            'price' => 450.0000,
        ]);

        expect($supplierPrice->getPriceForQuantity(5))->toBe('500.0000');
    });

    it('cascades delete when supplier price is deleted', function (): void {
        $supplierPrice = SupplierPrice::factory()->create();

        SupplierPriceTier::create([
            'supplier_price_id' => $supplierPrice->id,
            'min_quantity' => 1,
            'max_quantity' => 10,
            'price' => 100.0000,
        ]);

        $supplierPrice->delete();

        expect(SupplierPriceTier::query()->where('supplier_price_id', $supplierPrice->id)->count())->toBe(0);
    });
});
