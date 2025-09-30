<?php

namespace App\Http\Controllers;

use App\Services\CommentService;
use App\Services\TaskService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(protected CommentService $commentService, protected TaskService $taskService)
    {
    }

    /**
     * Display a paginated list of comments.
     */
    public function index(Request $request, int $taskId)
    {
        $this->taskService->findTask($taskId);

        $filters = $request->only(['search', 'per_page']);
        $comments = $this->commentService->listByTask($taskId, $filters);

        return $this->jsonResponseWithPagination($comments);
    }

    /**
     * POST /api/tasks/{task_id}/comments
     */
    public function store(Request $request, int $taskId)
    {
        $user = $request->user();
        $task = $this->taskService->findTask($taskId);

        if ($user->role !== 'manager' && $user->id !== $task->assigned_to) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $validated['user_id'] = $user->id;

        $comment = $this->commentService->addComment($taskId, $validated);

        return response()->json([
            'message' => 'Comment added successfully',
            'comment' => $comment,
        ], 201);
    }
}
