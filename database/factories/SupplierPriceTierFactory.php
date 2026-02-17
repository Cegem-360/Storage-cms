<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\SupplierPrice;
use App\Models\SupplierPriceTier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SupplierPriceTier>
 */
final class SupplierPriceTierFactory extends Factory
{
    protected $model = SupplierPriceTier::class;

    public function definition(): array
    {
        return [
            'supplier_price_id' => SupplierPrice::factory(),
            'min_quantity' => fake()->numberBetween(1, 100),
            'max_quantity' => fake()->numberBetween(101, 500),
            'price' => fake()->randomFloat(4, 1, 5000),
        ];
    }
}
