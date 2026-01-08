<?php

namespace App\Repositories;

use App\Contracts\Project\ProjectRepoInterface;
use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProjectRepository implements ProjectRepoInterface
{

    /**
     * Create a new project.
     *
     * @param array $data
     * @return Project
     */
    public function create(array $data): Project
    {
        return Project::create($data);
    }

    /**
     * Update a project.
     * 
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return Project::owned()->where('id', $id)->update($data);
    }


    /**
     * Remove project
     *
     * @param int $id
     * @return bool
     */
    public function remove(int $id): bool
    {
        return Project::owned()->where('id', $id)->delet();
    }

    /**
     * List projects.
     *
     * @param int $records
     * @return LengthAwarePaginator
     */
    public function getAll(int $records): LengthAwarePaginator
    {
        return Project::owned()->paginate($records);
    }
}