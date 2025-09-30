<?php

namespace App\Services;

use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Models\Comment;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CommentService
{
    public function __construct(protected CommentRepositoryInterface $repository)
    {
    }

    public function listByTask(int $taskId, array $filters): LengthAwarePaginator
    {
        return $this->repository->getByTask($taskId, $filters);
    }

    public function addComment(int $taskId, array $data): Comment
    {
        return $this->repository->createForTask($taskId, $data);
    }
}
