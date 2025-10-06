<?php

namespace App\Repositories\Eloquent;

use App\Models\Project;
use App\Pagination;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function getPaginated(array $filters = []): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? Pagination::DefaultPerPage->value;
        $search = $filters['search'] ?? null;
        $page = request('page', Pagination::DefaultPage->value);

        $version = Cache::get('projects_version', 1);

        //not great, not terrible because on every small update the cache would be invalidated and missed
        //there are multiple caching strategies but depends a lot on what business wants
        $cacheKey = "projects_v{$version}_page_{$page}_per_{$perPage}_search_{$search}";

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($filters, $perPage) {
            $query = Project::query()
                ->select('id', 'title', 'description', 'start_date', 'end_date', 'created_at')
                ->latest();

            if (!empty($filters['search'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('title', 'like', "%{$filters['search']}%")
                        ->orWhere('description', 'like', "%{$filters['search']}%");
                });
            }

            return $query->paginate($perPage);
        });
    }

    public function findById(int $id): Project
    {
        return Project::findOrFail($id);
    }

    public function create(array $data): Project
    {
        return Project::create($data);
    }

    public function update(int $id, array $data): Project
    {
        $project = Project::findOrFail($id);
        $project->update($data);

        return $project;
    }

    public function delete(int $id): bool
    {
        $project = Project::findOrFail($id);

        return (bool) $project->delete();
    }
}
