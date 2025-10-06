<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Pagination;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskRepository implements TaskRepositoryInterface
{
    public function getByProject(int $projectId, array $filters = []): LengthAwarePaginator
    {
        $query = Task::query()
            ->where('project_id', $projectId)
            ->select('id', 'title', 'description', 'status', 'due_date', 'assigned_to', 'created_at')
            ->latest();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', "%{$filters['search']}%")
                    ->orWhere('description', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $perPage = $filters['per_page'] ?? Pagination::DefaultPerPage->value;

        return $query->paginate($perPage);
    }

    public function findById(int $id): Task
    {
        return Task::findOrFail($id);
    }

    public function createForProject(array $data): Task
    {
        return Task::create($data);
    }

    public function update(int $id, array $data): Task
    {
        $task = Task::findOrFail($id);
        $task->update($data);

        return $task;
    }

    public function delete(int $id): bool
    {
        $task = Task::findOrFail($id);

        return (bool) $task->delete();
    }
}
