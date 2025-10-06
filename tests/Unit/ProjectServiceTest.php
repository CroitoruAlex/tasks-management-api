<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ProjectService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ProjectService::class);
    }

    #[Test]
    public function it_creates_a_new_project()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $data = [
            'title' => 'API Redesign',
            'description' => 'Upgrade project endpoints',
        ];

        $project = $this->service->createProject($data);

        $this->assertEquals('API Redesign', $project->title);
        $this->assertEquals($admin->id, $project->created_by);
    }

    #[Test]
    public function it_updates_existing_project()
    {
        $project = Project::factory()->create(['title' => 'Old Title']);

        $updated = $this->service->updateProject($project->id, ['title' => 'New Title']);

        $this->assertEquals('New Title', $updated->title);
    }

    /** @test */
    public function it_deletes_a_project()
    {
        $project = Project::factory()->create();

        $result = $this->service->deleteProject($project->id);

        $this->assertTrue($result);
        $this->assertSoftDeleted('projects', ['id' => $project->id]);
    }
}
