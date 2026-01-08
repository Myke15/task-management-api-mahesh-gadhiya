<?php

namespace App\Contracts\Task;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TaskRepoInterface
{
    /**
     * Create a task.
     *
     * @param array $data
     * @return Task
    */
    public function create(array $data): Task;

    /**
     * Update a task.
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * Remove task
     *
     * @param int $id
     * @param bool $forceDelete
     * @return bool
     */
    public function remove(int $id, bool $forceDelete = false): bool;

    /**
     * List project tasks.
     *
     * @param int $projectId
     * @param array $filters
     * @param string $orderBy
     * @param int $records
     * @return LengthAwarePaginator
     */
    public function getAll(int $projectId, array $filters, string $orderBy, int $records): LengthAwarePaginator;
    
}
