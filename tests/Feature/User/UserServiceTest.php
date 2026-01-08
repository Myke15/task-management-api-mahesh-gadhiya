<?php

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create user using user service', function () {

    $userData = User::factory()->make([
        'email'     => 'jane@example.com',
        'name'      => 'Jane Doe',
        'password'  => 'test@123'
    ])->makeVisible('password')->toArray();

    $service = app()->make(UserService::class);
    $user = $service->createUser($userData);

    expect($user)->toBeInstanceOf(User::class);
    
    $this->assertDatabaseHas('users', [
        'email' => 'jane@example.com',
        'name'  => 'Jane Doe',
    ]);

    expect($user->name)->toBe('Jane Doe');
    expect($user->email)->toBe('jane@example.com');
});


it('rollback transaction in case of exception', function () {
    
    $this->mock(UserService::class, function ($mock) {
        $mock->shouldReceive('createUser')
            ->once()
            ->andThrow(new Exception('Service failure'));
    });

    $signUpData = [
        'name'      => 'John Doe',
        'email'     => 'john.doe@email.com',
        'password'  => 'test@123',
        'password_confirmation' => 'test@123'
    ];

    $this->postJson(route('api.register'), $signUpData);

    $this->assertDatabaseCount('users', 0);
});

it('throw exception when creating a user fails', function () {

    $this->mock(UserRepository::class, function ($mock) {
        $mock->shouldReceive('create')
            ->once()
            ->andThrow(new Exception('Service failure'));
    });

    $userData = User::factory()->make()->makeVisible('password')->toArray();

    $service = app()->make(UserService::class);
    $service->createUser($userData);
    
})->throws(Exception::class, 'Service failure');
