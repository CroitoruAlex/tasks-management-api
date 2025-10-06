<?php

namespace Tests\Feature;

use App\Models\Project;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_show_a_project()
    {

        $user = User::factory()->create(['role' => 'user']);
        $project = Project::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->get('/api/projects/' . $project->id);

        $response->assertStatus(200);
    }

    #[Test]
    public function admin_can_create_project()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/projects', [
            'title' => 'New Project',
            'description' => 'Project Description',
        ]);

        $response->assertStatus(201);
    }

    #[Test]
    public function non_admin_cannot_create_project()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/projects', [
            'title' => 'Invalid Project'
        ]);

        $response->assertStatus(403);
    }

    #[Test]
    public function admin_can_update_project()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')->putJson('/api/projects/' . $project->id, [
            'title' => 'Update Project Title',
            'description' => 'Update Project Description',
        ]);

        $response->assertStatus(200);
    }

    #[Test]
    public function non_admin_cannot_update_project()
    {
        $user = User::factory()->create(['role' => 'user']);
        $project = Project::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->putJson('/api/projects/' . $project->id, [
            'title' => 'Update Project Title',
            'description' => 'Update Project Description',
        ]);

        $response->assertStatus(403);
    }

    #[Test]
    public function admin_can_delete_project()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')->delete('/api/projects/' . $project->id);

        $response->assertStatus(200);
    }

    #[Test]
    public function non_admin_cannot_delete_project()
    {
        $user = User::factory()->create(['role' => 'user']);
        $project = Project::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->delete('/api/projects/' . $project->id);

        $response->assertStatus(403);
    }
}
