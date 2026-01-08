<?php

namespace App\Repositories;

use App\Contracts\Task\TaskRepoInterface;
use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskRepository implements TaskRepoInterface
{

    /**
     * Create a new task.
     *
     * @param array $data
     * @return Task
     */
    public function create(array $data): Task
    {
        return Task::create($data);
    }

    /**
     * Update a task.
     * 
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return Task::where('id', $id)->update($data);
    }


    /**
     * Remove task
     *
     * @param int $id
     * @param bool $forceDelete
     * @return bool
     */
    public function remove(int $id, bool $forceDelete = false): bool
    {
        return Task::where('id', $id)
            ->when($forceDelete, function ($q) {
                return $q->forceDelete();
            }, function ($q) {
                return $q->delete();
            });
    }

    /**
     * List project tasks.
     *
     * @param int $projectId
     * @param array $filters
     * @param string $orderBy
     * @param int $records
     * @return LengthAwarePaginator
     */
    public function getAll(int $projectId, array $filters = [], string $orderBy = 'created_at', int $records = 10): LengthAwarePaginator
    {
        return Task::where('project_id', $projectId)
                ->when(!empty($filters), function ($q) use ($filters) {
                    return $q->where($filters);
                })
                ->orderBy($orderBy)
                ->paginate($records);
    }
}