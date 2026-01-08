<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('register a new user successfully', function () {
    
    $signUpData = [
        'name'      => 'John Doe',
        'email'     => 'john.doe@email.com',
        'password'  => 'test@123',
        'password_confirmation' => 'test@123'
    ];

    $response = $this->postJson(route('api.register'), $signUpData);

    $response->assertStatus(200)
            ->assertJsonStructure([
                'result',
                'message'
            ])
            ->assertJson([
                'result'    => true,
                'message'   => 'Registration success.'
            ]);
});

it('verify database has a user upon successful registration', function () {
    
    $signUpData = [
        'name'      => 'John Doe',
        'email'     => 'john.doe@email.com',
        'password'  => 'test@123',
        'password_confirmation' => 'test@123'
    ];

    $response = $this->postJson(route('api.register'), $signUpData);

    $response->assertStatus(200);

    $this->assertDatabaseHas('users', [
        'email' => 'john.doe@email.com',
        'name' => 'John Doe',
    ]);
});


it('validate registration request', function () {

    $signUpData = [
        'name'      => 'John Doe',
        'email'     => 'john.doe@email.com'
    ];

    $response = $this->postJson(route('api.register'), $signUpData);

    $response->assertStatus(422)
            ->assertJsonStructure([
                'result',
                'message',
                'all_failed_validations'
            ]);
});