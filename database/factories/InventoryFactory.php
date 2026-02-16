<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\InventoryStatus;
use App\Enums\InventoryType;
use App\Models\Employee;
use App\Models\Team;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
final class InventoryFactory extends Factory
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
            'inventory_number' => 'INV-'.fake()->unique()->numerify('######'),
            'warehouse_id' => Warehouse::factory(),
            'conducted_by' => Employee::factory(),
            'inventory_date' => fake()->dateTimeBetween('-30 days', 'now'),
            'status' => InventoryStatus::DRAFT,
            'type' => fake()->randomElement(InventoryType::cases()),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => InventoryStatus::COMPLETED,
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => InventoryStatus::APPROVED,
        ]);
    }
}
