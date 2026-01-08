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
     * @param array{user_id: int, name: string, description: string, status: string} $data
     * @return Project
     */
    public function create(array $data): Project
    {
        return Project::create($data);
    }

    /**
     * Update a project.
     * 
     * @param int $id
     * @param array{name?: string, description?: string, status?: string} $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return (bool) Project::owned()->where('id', $id)->update($data);
    }


    /**
     * Remove project
     *
     * @param int $id
     * @param bool $forceDelete
     * @return bool
     */
    public function remove(int $id, bool $forceDelete = false): bool
    {
        return Project::owned()->where('id', $id)
            ->when($forceDelete, function ($q) {
                return $q->forceDelete();
            }, function ($q) {
                return $q->delete();
            });
    }

    /**
     * List projects.
     *
     * @param int $records
     * @return LengthAwarePaginator<int, Project>
     */
    public function getAll(int $records): LengthAwarePaginator
    {
        return Project::owned()->paginate($records);
    }
}