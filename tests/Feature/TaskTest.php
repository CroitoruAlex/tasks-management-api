<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function manager_can_update_task()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $project = Project::factory()->create(['created_by' => $manager->id]);
        $task = Task::factory()->create([
            'project_id' => $project->id
        ]);

        $response = $this->actingAs($manager, 'sanctum')->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Task Title',
        ]);

        $response->assertStatus(200);
    }

    #[Test]
    public function user_cannot_create_task()
    {
        $user = User::factory()->create(['role' => 'user']);
        $manager = User::factory()->create(['role' => 'manager']);
        $project = Project::factory()->create(['created_by' => $manager->id]);

        $response = $this->actingAs($user, 'sanctum')->postJson("/api/projects/$project->id/tasks", [
            'title' => 'new Task Title',
        ]);

        $response->assertStatus(403);
    }

    #[Test]
    public function manager_can_destroy_task()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $project = Project::factory()->create(['created_by' => $manager->id]);
        $task = Task::factory()->create(['project_id' => $project->id]);

        $response = $this->actingAs($manager, 'sanctum')->delete("/api/tasks/{$task->id}");

        $response->assertStatus(200);
    }


    #[Test]
    public function assigned_user_can_update_task()
    {
        $user = User::factory()->create(['role' => 'user']);
        $manager = User::factory()->create(['role' => 'manager']);
        $project = Project::factory()->create(['created_by' => $manager->id]);
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'assigned_to' => $user->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Task Title',
        ]);

        $response->assertStatus(200);
    }

    #[Test]
    public function non_manager_cannot_destroy_task()
    {
        $user = User::factory()->create(['role' => 'user']);

        $manager = User::factory()->create(['role' => 'manager']);
        $project = Project::factory()->create(['created_by' => $manager->id]);
        $task = Task::factory()->create(['project_id' => $project->id]);

        $response = $this->actingAs($user, 'sanctum')->delete("/api/tasks/{$task}");

        $response->assertStatus(403);
    }
}
