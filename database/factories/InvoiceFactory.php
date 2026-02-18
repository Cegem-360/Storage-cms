<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\InvoiceStatus;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
final class InvoiceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'invoice_number' => 'INV-'.fake()->unique()->numerify('######'),
            'order_id' => Order::factory(),
            'receipt_id' => null,
            'supplier_id' => null,
            'customer_id' => null,
            'issued_by' => Employee::factory(),
            'invoice_date' => fake()->dateTimeBetween('-30 days', 'now'),
            'due_date' => fake()->dateTimeBetween('now', '+30 days'),
            'status' => InvoiceStatus::DRAFT,
            'subtotal' => 0,
            'tax_total' => 0,
            'total_amount' => 0,
            'currency' => 'HUF',
            'payment_method' => null,
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function issued(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => InvoiceStatus::ISSUED,
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => InvoiceStatus::PAID,
        ]);
    }

    public function overdue(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => InvoiceStatus::OVERDUE,
            'due_date' => fake()->dateTimeBetween('-30 days', '-1 day'),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => InvoiceStatus::CANCELLED,
        ]);
    }
}
