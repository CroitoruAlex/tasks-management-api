<?php

namespace App\Repositories\Interfaces;

use App\Models\Comment;
use Illuminate\Pagination\LengthAwarePaginator;

interface CommentRepositoryInterface
{
    public function getByTask(int $taskId, array $filters = []): LengthAwarePaginator;
    public function createForTask(int $taskId, array $data): Comment;
}
