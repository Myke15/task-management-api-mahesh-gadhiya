<?php

namespace App\Providers;

use App\Contracts\User\UserRepoInterface;
use App\Contracts\User\UserServiceInterface;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

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
        //TODO::Format Response in Json
        RateLimiter::for('guest-limit', function (Request $request) {
            return Limit::perMinute(3)->by($request->input('email'));
        });
    }
}
