<?php

namespace App\Http\Controllers\API;

use App\Contracts\Task\TaskServiceInterface;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Http\Controllers\Controller;
use App\Http\Requests\ListTasksRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{

    use AuthorizesRequests;

    /**
     * TaskController constructor.
     *
     * @param TaskServiceInterface $taskService
     */
    public function __construct(
        public TaskServiceInterface $taskService
    ) { }

    /**
     * Display a listing of the resource.
     */
    public function index(ListTasksRequest $request, Project $project)
    {
        try {
            
            $filters = $request->filters ?? [];
            $orderBy = $request->order_by ?? 'created_at';
            $records = $request->records ?? 15;

            $tasks = $this->taskService->listTask($project, $filters, $orderBy, $records);

            return (new TaskCollection($tasks))->response();

        } catch (Exception $e) {

            Log::error('Error While Listing Project Tasks', [$e->getMessage(), $e->getTraceAsString()]);

            return $this->responseInternalServerError('Unable to list project tasks, please try again later!');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request, Project $project): JsonResponse
    {
        try {

            $data = $request->validated();

            $this->taskService->createTask($project, $data);
            
            return $this->responseSuccess('Task created successfully!');

        } catch (Exception $e) {

            Log::error('Error While Creating Task', [$e->getMessage(), $e->getTraceAsString()]);

            return $this->responseInternalServerError('Unable to create task, please try again later!');

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): TaskResource|JsonResponse
    {
        $this->authorize('view', $task);
        
        try {   
            
            return new TaskResource($task);

        } catch (Exception $e) {

            Log::error('Error While Showing Task Detail', [$e->getMessage(), $e->getTraceAsString()]);

            return $this->responseInternalServerError('Unable to fetch task, please try again later!');

        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        try {
            
            $this->taskService->updateTask($task, $request->validated());

            return $this->responseSuccess('Task updated successfully!');

        } catch (Exception $e) {

            Log::error('Error While Updating Task', [$e->getMessage(), $e->getTraceAsString()]);

            return $this->responseInternalServerError('Unable to update task, please try again later!');

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        try {

            $this->taskService->removeTask($task);

            return $this->responseSuccess('Task removed!');

        } catch (Exception $e) {

            Log::error('Error While Removing Task', [$e->getMessage(), $e->getTraceAsString()]);

            return $this->responseInternalServerError('Unable to remove task, please try again later!');

        }
    }
}
