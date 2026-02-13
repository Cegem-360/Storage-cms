<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\DiscrepancyType;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InventoryLine>
 */
final class InventoryLineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $systemQuantity = fake()->numberBetween(10, 100);

        return [
            'inventory_id' => Inventory::factory(),
            'product_id' => Product::factory(),
            'system_quantity' => $systemQuantity,
            'actual_quantity' => $systemQuantity,
            'unit_cost' => fake()->randomFloat(2, 10, 500),
            'condition' => DiscrepancyType::MATCH,
            'batch_number' => fake()->optional()->bothify('BATCH-####'),
            'expiry_date' => fake()->optional()->dateTimeBetween('+1 month', '+2 years'),
            'note' => fake()->optional()->sentence(),
        ];
    }

    public function withShortage(int $shortage = 5): static
    {
        return $this->state(fn (array $attributes): array => [
            'actual_quantity' => max(0, $attributes['system_quantity'] - $shortage),
            'condition' => DiscrepancyType::SHORTAGE,
        ]);
    }

    public function withOverage(int $overage = 5): static
    {
        return $this->state(fn (array $attributes): array => [
            'actual_quantity' => $attributes['system_quantity'] + $overage,
            'condition' => DiscrepancyType::OVERAGE,
        ]);
    }
}
