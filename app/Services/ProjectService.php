<?php

namespace App\Services;

use App\Models\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use \Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ProjectService
{
    public function __construct(protected ProjectRepositoryInterface $repository)
    {
    }

    public function listProjects(array $filters): LengthAwarePaginator
    {
        return $this->repository->getPaginated($filters);
    }

    public function createProject(array $data): Project
    {
        $data['created_by'] = auth()->id();

        $project = $this->repository->create($data);
        Cache::flush(); // would be bad in production because it invalidates unrelated data

        return $project;
    }

    public function updateProject(int $id, array $data): Project
    {
        $project = $this->repository->update($id, $data);
        Cache::flush();

        return $project;
    }

    public function deleteProject(int $id): bool
    {
        $deleted = $this->repository->delete($id);
        Cache::flush();

        return $deleted;
    }

    public function findProject(int $id)
    {
        return $this->repository->findById($id);
    }
}
