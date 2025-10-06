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
        Cache::increment('projects_version');

        return $project;
    }

    public function updateProject(int $id, array $data): Project
    {
        $project = $this->repository->update($id, $data);
        Cache::increment('projects_version');

        return $project;
    }

    public function deleteProject(int $id): bool
    {
        $deleted = $this->repository->delete($id);
        Cache::increment('projects_version');

        return $deleted;
    }

    public function findProject(int $id)
    {
        return $this->repository->findById($id);
    }
}
