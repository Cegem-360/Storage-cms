<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AiTokenUsage;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<AiTokenUsage> */
final class AiTokenUsageFactory extends Factory
{
    protected $model = AiTokenUsage::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'month' => now()->format('Y-m'),
            'prompt_tokens' => fake()->numberBetween(0, 10000),
            'completion_tokens' => fake()->numberBetween(0, 5000),
            'total_tokens' => fn (array $attributes) => $attributes['prompt_tokens'] + $attributes['completion_tokens'],
        ];
    }
}
