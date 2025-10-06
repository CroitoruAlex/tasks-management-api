<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
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

    #[Test]
    public function it_creates_a_task_for_a_project()
    {
        Notification::fake();

        $project = Project::factory()->create();
        $user = User::factory()->create();

        $data = [
            'title' => 'Implement Login',
            'description' => 'Add authentication endpoint',
            'status' => 'pending',
            'assigned_to' => $user->id,
            'project_id' => $project->id,
        ];

        $task = $this->service->createTask($data);

        $this->assertEquals($project->id, $task->project_id);
        Notification::assertSentTo($user, TaskAssignedNotification::class);
        $this->assertEquals('Implement Login', $task->title);
    }

    #[Test]
    public function it_updates_task_successfully()
    {
        $task = Task::factory()->create(['title' => 'Old']);

        $updated = $this->service->updateTask($task->id, ['title' => 'New']);

        $this->assertEquals('New', $updated->title);
    }
}
