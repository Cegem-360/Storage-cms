<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Team;
use App\Models\TeamSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeamSetting>
 */
final class TeamSettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'key' => fake()->unique()->word(),
            'value' => fake()->word(),
        ];
    }
}
