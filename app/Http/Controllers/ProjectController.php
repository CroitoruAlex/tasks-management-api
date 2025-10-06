<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProjectRequest;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(protected ProjectService $projectService)
    {
    }

    /**
     * Display a paginated list of projects.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'per_page']);
        $projects = $this->projectService->listProjects($filters);

        return $this->jsonResponseWithPagination($projects);
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project = $this->projectService->createProject($validated);

        return response()->json([
            'project' => $project,
        ], 201);
    }

    /**
     * Display a specific project
     */
    public function show(int $id)
    {
        $project = $this->projectService->findProject($id);

        return response()->json([
            'project' => $project,
        ]);
    }

    /**
     * Update the specified project in storage.
     */
    public function update(UpdateProjectRequest $request, string $id)
    {
        $validated = $request->validated();

        $project = $this->projectService->updateProject($id, $validated);

        return response()->json([
            'project' => $project,
        ]);
    }

    /**
     * Remove the project resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = $this->projectService->deleteProject($id);

        return response()->json([
            'message' => $deleted
                ? 'Project deleted successfully'
                : 'Failed to delete project',
        ]);
    }
}
