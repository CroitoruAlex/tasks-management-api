<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Notification;

class TaskService
{
    public function __construct(protected TaskRepositoryInterface $repository)
    {
    }

    public function listByProject(int $projectId, array $filters): LengthAwarePaginator
    {
        return $this->repository->getByProject($projectId, $filters);
    }

    public function findTask(int $id): Task
    {
        return $this->repository->findById($id);
    }

    public function createTask(array $data): Task
    {
        $task = $this->repository->createForProject($data);

        if (!empty($task->assigned_to)) {
            $user = User::find($task->assigned_to);

            if ($user) {
                Notification::send($user, new TaskAssignedNotification($task));
            }
        }

        return $task;
    }

    public function updateTask(int $id, array $data): Task
    {
        return $this->repository->update($id, $data);
    }

    public function deleteTask(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
