<?php

namespace App\Contracts\Project;

use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProjectRepoInterface
{
    /**
     * Create a project.
     *
     * @param array $data
     * @return Project
    */
    public function create(array $data): Project;

    /**
     * Update a project.
     * 
     * @param int $id
     * @param array $data
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
     * @return LengthAwarePaginator
     */
    public function getAll(int $records): LengthAwarePaginator;
    
}
