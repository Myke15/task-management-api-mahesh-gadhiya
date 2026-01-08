<?php

namespace App\Services;

use App\Contracts\Task\TaskRepoInterface;
use App\Contracts\Task\TaskServiceInterface;
use App\Models\Project;
use App\Models\Task;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskService implements TaskServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public TaskRepoInterface $taskRepo
    ) {}

    
    /**
     * Create a new task.
     *
     * @param array $data
     * @return Task
     */
    public function createTask(Project $project, array $data): Task
    {
        try {

            DB::beginTransaction();
            //Create Tasks
            $data['project_id'] = $project->id;
            $task = $this->taskRepo->create($data);
            
            DB::commit();

            // TODO:: Clear Cache

            return $task;

        } catch (Exception $e) {
            
            DB::rollBack();

            throw $e;
        }
        
    }

    /**
     * Update a task
     *
     * @param Task $task
     * @param array $data
     * @return bool
     */
    public function updateTask(Task $task, array $data): bool
    {
        try {

            DB::beginTransaction();
            //Update task
            $result = $this->taskRepo->update($task->id, $data);
            
            DB::commit();

            // TODO:: Clear Cache

            return (bool) $result;

        } catch (Exception $e) {
            
            DB::rollBack();

            throw $e;
        }

    }


    /**
     * Delete a task
     *
     * @param Task $task
     * @return bool
     */
    public function removeTask(Task $task): bool
    {
        try {

            //Remove task
            DB::beginTransaction();
            
            $result = $this->taskRepo->remove($task->id);

            DB::commit();

            // TODO:: Clear Cache

            return $result;

        } catch (Exception $e) {
            
            DB::rollBack();

            throw $e;
        }
        
    }

    /**
     * List project tasks.
     *
     * @param Project $project
     * @param array $filters
     * @param string $orderBy
     * @param int $records
     * @return LengthAwarePaginator
     */
    public function listTask(Project $project, array $filters, string $orderBy, int $records): LengthAwarePaginator
    {
        //Remove null or empty value
        //TODO:: Implement Caching
        $filters = array_filter($filters);
        return $this->taskRepo->getAll($project->id, $filters, $orderBy, $records);
    }
}