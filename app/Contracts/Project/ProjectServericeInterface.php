<?php

namespace App\Contracts\Project;

use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProjectServiceInterface
{

    /**
     * List projects.
     *
     * @param int $records
     * @return LengthAwarePaginator
     */
    public function listProjects(int $records): LengthAwarePaginator;

    /**
     * Create a project.
     *
     * @param array $data
     * @return Project
     */
    public function createProject(array $data): Project;

    /**
     * Update a project
     *
     * @param Project $project
     * @param array $data
     * @return bool
     */
    public function updateProject(Project $project, array $data): bool;

    /**
     * Delete a project
     *
     * @param Project $project
     * @return bool
     */
    public function removeProject(Project $project): bool;
}
