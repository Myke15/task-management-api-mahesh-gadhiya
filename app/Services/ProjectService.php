<?php

namespace App\Services;

use App\Contracts\Project\ProjectServiceInterface;
use App\Contracts\Project\ProjectRepoInterface;
use App\Models\Project;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProjectService implements ProjectServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public ProjectRepoInterface $projectRepo
    ) {}

    /**
     * Create a new project.
     *
     * @param array $data
     * @return Project
     * @throws Exception
     */
    public function createProject(array $data): Project
    {
        DB::beginTransaction();
        
        //Create Project
        $project = $this->projectRepo->create($data);
        
        DB::commit();

        //TODO::clear cache

        return $project;
    }

    /**
     * Update a project.
     *
     * @param Project $project
     * @param array $data
     * @return bool
     */
    public function updateProject(Project $project, array $data): bool
    {
        DB::beginTransaction();
        //Update Project
        $result = $this->projectRepo->update($project->id, $data);
        
        DB::commit();

        //TODO::clear cache

        return (bool) $result;
    }


    /**
     * Delete a project
     *
     * @param Project $project
     * @return bool
     */
    public function removeProject(Project $project): bool
    {
        //Remove all tasks of the project firsts
        DB::beginTransaction();
        
        //Remove projects
        $result = $this->projectRepo->remove($project->id);

        DB::commit();

        //TODO::clear cache

        return $result;
    }

    /**
     * List projects.
     *
     * @param int $records
     * @return LengthAwarePaginator
     */
    public function listProjects(int $records = 15): LengthAwarePaginator
    {
        return $this->projectRepo->getAll($records);
    }
}