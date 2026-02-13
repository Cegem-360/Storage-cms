<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ProductCondition;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReceiptLine>
 */
final class ReceiptLineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $expected = fake()->numberBetween(1, 100);

        return [
            'receipt_id' => Receipt::factory(),
            'product_id' => Product::factory(),
            'warehouse_id' => Warehouse::factory(),
            'quantity_expected' => $expected,
            'quantity_received' => $expected,
            'unit_price' => fake()->randomFloat(2, 1, 500),
            'condition' => ProductCondition::GOOD,
            'expiry_date' => fake()->optional()->dateTimeBetween('+1 month', '+2 years'),
            'batch_number' => fake()->optional()->bothify('BATCH-####'),
            'note' => fake()->optional()->sentence(),
        ];
    }

    public function withVariance(): static
    {
        return $this->state(fn (array $attributes): array => [
            'quantity_received' => max(0, $attributes['quantity_expected'] - fake()->numberBetween(1, 10)),
        ]);
    }

    public function damaged(): static
    {
        return $this->state(fn (array $attributes): array => [
            'condition' => ProductCondition::DAMAGED,
        ]);
    }
}
