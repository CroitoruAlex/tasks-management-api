<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'start_date' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+2 months'),
            'created_by' => User::inRandomOrder()->first()?->id ?? User::factory(),
        ];
    }
}
