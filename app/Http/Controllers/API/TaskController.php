<?php

namespace App\Http\Controllers\API;

use App\Contracts\Task\TaskServiceInterface;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Http\Controllers\Controller;
use App\Http\Requests\ListTasksRequest;
use App\Http\Resources\TaskCollection;
use App\Models\Project;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{

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
    public function show(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
