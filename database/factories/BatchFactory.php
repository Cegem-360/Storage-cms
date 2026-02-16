<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\QualityStatus;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Batch>
 */
final class BatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'batch_number' => 'BATCH-'.fake()->unique()->numerify('#####'),
            'product_id' => Product::factory(),
            'supplier_id' => Supplier::factory(),
            'quantity' => fake()->numberBetween(1, 500),
            'quality_status' => fake()->randomElement(QualityStatus::cases())->value,
            'manufacture_date' => fake()->optional()->dateTimeBetween('-1 year', 'now'),
            'expiry_date' => fake()->optional()->dateTimeBetween('now', '+2 years'),
            'serial_numbers' => null,
        ];
    }

    public function pendingCheck(): static
    {
        return $this->state(fn (array $attributes) => [
            'quality_status' => QualityStatus::PENDING_CHECK->value,
        ]);
    }

    public function passed(): static
    {
        return $this->state(fn (array $attributes) => [
            'quality_status' => QualityStatus::PASSED->value,
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'quality_status' => QualityStatus::FAILED->value,
        ]);
    }
}
