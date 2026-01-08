<?php

namespace App\Contracts\Task;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TaskServiceInterface
{

    /**
     * List project tasks.
     *
     * @param Project $project
     * @param array{status?: string, priority?: string} $filters
     * @param string $orderBy
     * @param int $records
     * @return LengthAwarePaginator<int, Task>
     */
    public function listTask(Project $project, array $filters, string $orderBy, int $records): LengthAwarePaginator;

    /**
     * Create a task.
     *
     * @param Project $project
     * @param array{title: string, description: string, status: string, priority: string, due_date?: string} $data
     * @return Task
     */
    public function createTask(Project $project, array $data): Task;

    /**
     * Update a task
     *
     * @param Task $task
     * @param array{title?: string, description?: string, status?: string, priority?: string, due_date?: string} $data
     * @return bool
     */
    public function updateTask(Task $task, array $data): bool;

    /**
     * Delete a task
     *
     * @param Task $task
     * @return bool
     */
    public function removeTask(Task $task): bool;
}
