<?php

namespace App\Services;

use App\Contracts\Project\ProjectServiceInterface;
use App\Contracts\Task\TaskRepoInterface;
use App\Contracts\Project\ProjectRepoInterface;
use App\Models\Project;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ProjectService implements ProjectServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public ProjectRepoInterface $projectRepo,
        public TaskRepoInterface $taskRepo
    ) {}
    
    /**
     * Get the cache key for the user's projects.
     *
     * @return string
     */
    private function getCacheKey()
    {
        return 'u:' . auth()->id() . ':prj';
    }

    /**
     * Create a new project.
     *
     * @param array $data
     * @return Project
     * @throws Exception
     */
    public function createProject(array $data): Project
    {
        try {

            DB::beginTransaction();
            
            //Create Project
            $project = $this->projectRepo->create($data);
            
            DB::commit();

            Cache::forget($this->getCacheKey());

            return $project;

        } catch (Exception $e) {
            
            DB::rollBack();

            throw $e;
        }
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
        try {
            
            DB::beginTransaction();
            //Update Project
            $result = $this->projectRepo->update($project->id, $data);
            
            DB::commit();

            Cache::forget($this->getCacheKey());

            return (bool) $result;

        } catch (Exception $e) {
            
            DB::rollBack();

            throw $e;
        }
        
    }


    /**
     * Delete a project
     *
     * @param Project $project
     * @return bool
     */
    public function removeProject(Project $project): bool
    {
        try {
            //Remove all tasks of the project firsts
            DB::beginTransaction();

            $this->taskRepo->removeProjectTasks($project->id);
            
            //Remove projects
            $result = $this->projectRepo->remove($project->id);

            DB::commit();

            Cache::forget($this->getCacheKey());

            return $result;

        } catch (Exception $e) {
            
            DB::rollBack();

            throw $e;
        }
        
    }

    /**
     * List projects.
     *
     * @param int $records
     * @return LengthAwarePaginator
     */
    public function listProjects(int $records = 15): LengthAwarePaginator
    {
        return Cache::remember($this->getCacheKey(), now()->addHour(), function () use ($records) {
            return $this->projectRepo->getAll($records);
        });
    }
}