<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function manager_can_update_task()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $project = Project::factory()->create(['created_by' => $manager->id]);
        $task = Task::factory()->create(['project_id' => $project->id]);

        $response = $this->actingAs($manager, 'sanctum')->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Task Title',
        ]);

        $response->assertStatus(200);
    }
}
