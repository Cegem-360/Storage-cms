<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ReceiptStatus;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Team;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Receipt>
 */
final class ReceiptFactory extends Factory
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
            'receipt_number' => 'REC-'.fake()->unique()->numerify('######'),
            'order_id' => Order::factory(),
            'warehouse_id' => Warehouse::factory(),
            'received_by' => Employee::factory(),
            'receipt_date' => fake()->dateTimeBetween('-30 days', 'now'),
            'status' => ReceiptStatus::PENDING,
            'total_amount' => 0,
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ReceiptStatus::COMPLETED,
        ]);
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ReceiptStatus::CONFIRMED,
        ]);
    }
}
