<?php

namespace App\Providers;

use App\Contracts\Task\TaskRepoInterface;
use App\Contracts\Task\TaskServiceInterface;
use App\Contracts\Project\ProjectRepoInterface;
use App\Contracts\Project\ProjectServiceInterface;
use App\Contracts\User\UserRepoInterface;
use App\Contracts\User\UserServiceInterface;
use App\Models\Project;
use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use App\Services\ProjectService;
use App\Services\UserService;
use App\Services\TaskService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //User Services
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(UserRepoInterface::class, UserRepository::class);

        //Project Services
        $this->app->bind(ProjectServiceInterface::class, ProjectService::class);
        $this->app->bind(ProjectRepoInterface::class, ProjectRepository::class);

        //Task Services
        $this->app->bind(TaskServiceInterface::class, TaskService::class);
        $this->app->bind(TaskRepoInterface::class, TaskRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('guest-limit', function (Request $request) {
            return Limit::perMinute(3)->by($request->input('email'));
        });

        Route::bind('project', function (string $value) {
            return Project::where('id', $value)->owned()->firstOrFail();
        });
    }
}
