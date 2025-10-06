<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function __construct(protected TaskService $taskService)
    {
    }


    /**
     * Display a paginated list of tasks.
     */
    public function index(Request $request, int $projectId)
    {
        $filters = $request->only(['search', 'status', 'per_page']);
        $tasks = $this->taskService->listByProject($projectId, $filters);

        return $this->jsonResponseWithPagination($tasks);
    }

    /**
     * Display a specific task
     */
    public function show(int $id)
    {
        $task = $this->taskService->findTask($id);
        return response()->json([
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(StoreTaskRequest $request, int $projectId)
    {
        $validated = $request->validated();

        $task = $this->taskService->createTask($projectId, $validated);

        return response()->json([
            'task' => $task,
        ], 201);
    }

    /**
     * Update the specified task in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validated = $request->validated();

        $updated = $this->taskService->updateTask($task->id, $validated);

        return response()->json([
            'task' => $updated,
        ]);
    }

    /**
     * Remove the task resource from storage.
     */
    public function destroy(Request $request, int $id)
    {
        if ($request->user()->role !== 'manager') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $deleted = $this->taskService->deleteTask($id);

        return response()->json([
            'message' => $deleted ? 'Task deleted successfully' : 'Failed to delete task',
        ]);
    }
}
