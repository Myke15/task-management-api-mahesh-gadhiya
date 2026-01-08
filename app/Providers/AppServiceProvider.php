<?php

namespace App\Providers;

use App\Contracts\User\UserRepoInterface;
use App\Contracts\User\UserServiceInterface;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
