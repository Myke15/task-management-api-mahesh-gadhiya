<?php

use App\Events\UserRegistered;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it fire event upon user sign up', function () {
    
    Event::fake();

    $signUpData = [
        'name'      => 'John Doe',
        'email'     => 'john.doe@example.com',
        'password'  => 'test@123'
    ];

    $userService = app()->make(\App\Services\UserService::class);
    $userService->createUser($signUpData);

    Event::assertDispatched(UserRegistered::class);
});