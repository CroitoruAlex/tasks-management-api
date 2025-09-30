<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['pending', 'in-progress', 'done']),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'project_id' => Project::inRandomOrder()->first()?->id ?? Project::factory(),
            'assigned_to' => User::inRandomOrder()->first()?->id ?? User::factory(),
        ];
    }
}

//Task::factory()->for($project)->for($user, 'assignee')->create();
