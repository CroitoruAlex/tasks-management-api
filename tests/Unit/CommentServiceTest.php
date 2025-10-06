<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use App\Services\CommentService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CommentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CommentService::class);
    }

    #[Test]
    public function it_creates_a_comment_for_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();

        $comment = $this->service->addComment($task->id, [
            'body' => 'Looks good!',
            'user_id' => $user->id,
        ]);

        $this->assertEquals($task->id, $comment->task_id);
        $this->assertEquals($user->id, $comment->user_id);
        $this->assertEquals('Looks good!', $comment->body);
    }
}
