<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

Route::group([
    'as' => 'api.',
], function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
