<?php

namespace App\Repositories\Eloquent;

use App\Models\Comment;
use App\Pagination;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class CommentRepository implements CommentRepositoryInterface
{
    public function getByTask(int $taskId, array $filters = []): LengthAwarePaginator
    {
        $query = Comment::query()
            ->where('task_id', $taskId)
            ->with('user:id,name,email')
            ->select('id', 'body', 'user_id', 'task_id', 'created_at')
            ->latest();

        if (!empty($filters['search'])) {
            $query->where('body', 'like', "%{$filters['search']}%");
        }

        $perPage = $filters['per_page'] ?? Pagination::DefaultPerPage->value;

        return $query->paginate($perPage);
    }

    public function createForTask(int $taskId, array $data): Comment
    {
        $data['task_id'] = $taskId;

        return Comment::create($data);
    }
}
