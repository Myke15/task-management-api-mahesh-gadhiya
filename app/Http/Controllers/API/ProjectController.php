<?php

namespace App\Http\Controllers\API;

use App\Contracts\Project\ProjectServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    /**
     * ProjectController constructor.
     *
     * @param ProjectServerInterface $projectService
     */
    public function __construct(
        public ProjectServiceInterface $projectService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
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
    public function show(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }
}
