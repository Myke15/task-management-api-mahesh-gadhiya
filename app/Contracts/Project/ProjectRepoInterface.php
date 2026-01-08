<?php

namespace App\Contracts\Project;

use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProjectRepoInterface
{
    /**
     * Create a project.
     *
     * @param array{user_id: int, name: string, description: string, status: string} $data
     * @return Project
    */
    public function create(array $data): Project;

    /**
     * Update a project.
     * 
     * @param int $id
     * @param array{name?: string, description?: string, status?: string} $data
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * Remove project
     *
     * @param int $id
     * @param bool $forceDelete
     * @return bool
     */
    public function remove(int $id, bool $forceDelete = false): bool;

    /**
     * List projects.
     *
     * @param int $records
     * @return LengthAwarePaginator<int, Project>
     */
    public function getAll(int $records): LengthAwarePaginator;
    
}
