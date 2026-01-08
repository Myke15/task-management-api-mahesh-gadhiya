<?php

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

// This trait ensures the database is wiped clean before each test
uses(RefreshDatabase::class);

it('can create a new user record in the database', function () {
    
    $userData = User::factory()->make([
        'email'     => 'jane@example.com',
        'name'      => 'Jane Doe',
        'password'  => 'test@123'
    ])->makeVisible('password')->toArray();

    $repository = new UserRepository();
    $user = $repository->create($userData);

    expect($user)->toBeInstanceOf(User::class);
    
    // Verify the data exists in the database
    $this->assertDatabaseHas('users', [
        'email' => 'jane@example.com',
        'name'  => 'Jane Doe',
    ]);

    // Verify the attributes match the input
    expect($user->name)->toBe('Jane Doe');
    expect($user->email)->toBe('jane@example.com');
});
