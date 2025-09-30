<?php

namespace Tests\Unit;

use App\Models\Task;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TaskService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(TaskService::class);
    }

    /** @test */
    public function it_creates_a_task_for_a_project()
    {
        $project = Project::factory()->create();
        $user = User::factory()->create();

        $data = [
            'title' => 'Implement Login',
            'description' => 'Add authentication endpoint',
            'status' => 'pending',
            'assigned_to' => $user->id,
        ];

        $task = $this->service->createTask($project->id, $data);

        $this->assertEquals($project->id, $task->project_id);
        $this->assertEquals('Implement Login', $task->title);
    }

    /** @test */
    public function it_updates_task_successfully()
    {
        $task = Task::factory()->create(['title' => 'Old']);

        $updated = $this->service->updateTask($task->id, ['title' => 'New']);

        $this->assertEquals('New', $updated->title);
    }
}
