<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_add_comment_to_task()
    {
        $user = User::factory()->create(['role' => 'user']);
        $project = Project::factory()->create(['created_by' => $user->id]);
        $task = Task::factory()->create(['project_id' => $project->id]);

        $response = $this->actingAs($user, 'sanctum')->postJson("/api/tasks/{$task->id}/comments", [
            'body' => 'This task needs to be reviewed soon.'
        ]);

        $response->assertStatus(201);
    }
}
