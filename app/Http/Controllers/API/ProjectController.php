<?php

namespace App\Http\Controllers\API;

use App\Contracts\Project\ProjectServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * ProjectController constructor.
     *
     * @param ProjectServiceInterface $projectService
     */
    public function __construct(
        public ProjectServiceInterface $projectService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {

            $projects = $this->projectService->listProjects($request->records ?? 15);

            return (new ProjectCollection($projects))->response();

        } catch (Exception $e) {

            Log::error('Error While Creating Project', [$e->getMessage(), $e->getTraceAsString()]);

            return $this->responseInternalServerError('Unable to fetch projects, please try again later!');

        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        try {
            
            $data = $request->validated();
            $data['user_id'] = Auth::user()->id;

            $this->projectService->createProject($data);

            return $this->responseSuccess('Project created successfully.');

        } catch (Exception $e) {

            Log::error('Error While Creating Project', [$e->getMessage(), $e->getTraceAsString()]);

            return $this->responseInternalServerError('Unable to create project, please try again later!');

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project): ProjectResource|JsonResponse
    {
        try {
            
            return new ProjectResource($project);

        } catch (Exception $e) {

            Log::error('Error While Showing Project Detail', [$e->getMessage(), $e->getTraceAsString()]);

            return $this->responseInternalServerError('Unable to fetch project, please try again later!');

        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        try {
            $this->projectService->updateProject($project, $request->validated());

            return $this->responseSuccess('Project updated successfully.');

        } catch (Exception $e) {

            Log::error('Error While Updating Project', [$e->getMessage(), $e->getTraceAsString()]);

            return $this->responseInternalServerError('Unable to update project, please try again later!');

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project): JsonResponse
    {
        try {
            
            $this->projectService->removeProject($project);

            return $this->responseSuccess('Project removed.');

        } catch (Exception $e) {

            Log::error('Error While Removing Project', [$e->getMessage(), $e->getTraceAsString()]);

            return $this->responseInternalServerError('Unable to remove project, please try again later!');

        }
    }
}
