<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_create_project()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/projects', [
            'title' => 'New Project',
            'description' => 'Project Description',
        ]);

        $response->assertStatus(201);
    }

    /** @test */
    public function non_admin_cannot_create_project()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/projects', [
            'title' => 'Invalid Project'
        ]);

        $response->assertStatus(403);
    }
}
