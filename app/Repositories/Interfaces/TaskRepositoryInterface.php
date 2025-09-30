<?php

namespace App\Repositories\Interfaces;

use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

interface TaskRepositoryInterface
{
    public function getByProject(int $projectId, array $filters = []): LengthAwarePaginator;
    public function findById(int $id): Task;
    public function createForProject(int $projectId, array $data);
    public function update(int $id, array $data);
    public function delete(int $id): bool;
}
