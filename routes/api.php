<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\TaskController;

Route::group([
    'as' => 'api.',
    'middleware' => ['throttle:guest-limit']
], function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::group([
    'as' => 'api.',
    'middleware' => ['throttle:100,1', 'auth:sanctum']
], function() {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/user', UserController::class)->name('profile');

    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('projects.tasks', TaskController::class)->only(['index', 'store'])->scoped();
    Route::apiResource('tasks', TaskController::class)->only(['show', 'update', 'destroy']);
});

