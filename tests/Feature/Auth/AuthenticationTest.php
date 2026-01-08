<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use App\Services\UserService;
use Illuminate\Http\Request;

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

it('validate login request', function () {

    $loginData = [
        'email'     => 'john.doe@email.com'
    ];
    
    $response = $this->postJson(route('api.login'), $loginData);

    $response->assertStatus(422)
            ->assertJsonStructure([
                'result',
                'message',
                'all_failed_validations'
            ]);
});

it('successfully sign in user', function () {

    User::factory()->create([
        'name'      => 'John Doe',
        'email'     => 'john.doe@email.com',
        'password'  => 'test@123'
    ]);

    $loginData = [
        'email'     => 'john.doe@email.com',
        'password'  => 'test@123'
    ];
    
    $response = $this->postJson(route('api.login'), $loginData);

    $response->assertStatus(200)
            ->assertJsonStructure([
                'result',
                'message',
                'token'
            ]);
});

it('it generate api token for user', function () {

    User::factory()->create([
        'name'      => 'John Doe',
        'email'     => 'john.doe@email.com',
        'password'  => 'test@123'
    ]);

    $loginData = [
        'email'     => 'john.doe@email.com',
        'password'  => 'test@123'
    ];
    
    $response = $this->postJson(route('api.login'), $loginData);

    $response->assertStatus(200);
    $this->assertDatabaseCount('personal_access_tokens', 1);

});


it('prevnt login for with invalid credentials', function () {

    User::factory()->create([
        'name'      => 'John Doe',
        'email'     => 'john.doe@email.com',
        'password'  => 'test@123'
    ]);

    $loginData = [
        'email'     => 'john.doe@email.com',
        'password'  => 'test@1233'
    ];
    
    $response = $this->postJson(route('api.login'), $loginData);

    $response->assertStatus(401)
            ->assertJsonStructure([
                'result',
                'message'
            ])->assertJson([
                'result'    => false,
                'message'   => 'Invalid credentials or unauthorized access'
            ]);
});

it('logout user', function () {

    $user = User::factory()->create([
        'name'      => 'John Doe',
        'email'     => 'john.doe@email.com',
        'password'  => 'test@123'
    ]);

    $token = $user->createToken('API-Token-TDD');
    
    $response = $this->postJson(route('api.logout'), [], [
        'Accept'        => 'application/json',
        'Authorization' => 'Bearer ' . $token->plainTextToken
    ]);

    $response->assertStatus(200)
            ->assertJsonStructure([
                'result',
                'message'
            ]);
    $this->assertDatabaseCount('personal_access_tokens', 0);
});

it('throttle login attempts for guest users', function () {

    $loginData = [
        'email'     => 'john.doe@email.com',
        'password'  => 'test@123'
    ];

    for ($i = 1; $i <= 4; $i++) {
        $response = $this->postJson(route('api.login'), $loginData);

        if ($i <=3 ) {
            $response->assertStatus(422);
        } else {
            $response->assertStatus(429)
                ->assertJsonStructure([
                    'result',
                    'message'
                ]);
        }
    }
});

it('throttle signup attempts for guest users', function () {

    $signUpData = [
        'name'      => 'John Doe',
        'email'     => 'john.doe@email.com',
        'password'  => 'test@123'
    ];

    for ($i = 1; $i <= 4; $i++) {
        $response = $this->postJson(route('api.register'), $signUpData);

        if ($i <=3 ) {
            $response->assertStatus(422);
        } else {
            $response->assertStatus(429)
                ->assertJsonStructure([
                    'result',
                    'message'
                ]);
        }
    }
});

it('returns 501 in case of unexpected exception for registration api', function () {

    $signUpData = [
        'name'      => 'John Doe',
        'email'     => 'john.doe@email.com',
        'password'  => 'test@123',
        'password_confirmation' => 'test@123'
    ];

    $this->mock(UserService::class, function ($mock) {
        $mock->shouldReceive('createUser')
            ->once()
            ->andThrow(new Exception('Service failure'));
    });

    
    $response = $this->postJson(route('api.register'), $signUpData);

    $response
        ->assertStatus(501)
        ->assertJson([
            'result'  => false,
            'message' => 'Unable to register user, please try again later!',
        ]);
});

it('returns 501 in case of unexpected exception for logout api', function () {

    Sanctum::actingAs(User::factory()->create());

    $this->mock(Request::class, function ($mock) {
        $mock->shouldReceive('user')->andThrow(new Exception());
    });
    
    $response = $this->postJson(route('api.logout'), []);

    $response
        ->assertStatus(501)
        ->assertJson([
            'result'  => false,
            'message' => 'Unable to logout user, please try again later!',
        ]);
});