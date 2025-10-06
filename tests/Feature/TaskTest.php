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
    public function user_can_list_tasks(): void
    {
        $user = User::factory()->create();
        $manager = User::factory()->create(['role' => 'manager']);
        $project = Project::factory()->create(['created_by' => $manager->id]);
        $task = Task::factory()->create([
            'project_id' => $project->id
        ]);

        $response = $this->actingAs($user, 'sanctum')->get("/api/projects/$project->id/tasks");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'pagination' => [
                    'total',
                    'per_page',
                    'current_page',
                    'last_page',
                ],
                'timestamp',
                'execution_time',
            ]);

    }

    #[Test]
    public function user_can_show_task(): void
    {
        $user = User::factory()->create();
        $manager = User::factory()->create(['role' => 'manager']);
        $project = Project::factory()->create(['created_by' => $manager->id]);
        $task = Task::factory()->create([
            'project_id' => $project->id
        ]);

        $response = $this->actingAs($user, 'sanctum')->get("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJson([
            'task' => $task->toArray(),
        ]);
    }

    #[Test]
    public function manager_can_update_task(): void
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
    public function manager_can_create_task(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $project = Project::factory()->create(['created_by' => $manager->id]);
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($manager, 'sanctum')->postJson("/api/projects/$project->id/tasks", [
            'title' => 'New Task Title',
            'assigned_to' => $user->id,
        ]);

        $response->assertStatus(201);
    }

    #[Test]
    public function user_cannot_create_task(): void
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
    public function manager_can_destroy_task(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $project = Project::factory()->create(['created_by' => $manager->id]);
        $task = Task::factory()->create(['project_id' => $project->id]);

        $response = $this->actingAs($manager, 'sanctum')->delete("/api/tasks/{$task->id}");

        $response->assertStatus(200);
    }


    #[Test]
    public function assigned_user_can_update_task(): void
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
    public function non_manager_cannot_destroy_task(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $manager = User::factory()->create(['role' => 'manager']);
        $project = Project::factory()->create(['created_by' => $manager->id]);
        $task = Task::factory()->create(['project_id' => $project->id]);

        $response = $this->actingAs($user, 'sanctum')->delete("/api/tasks/{$task}");

        $response->assertStatus(403);
    }
}
