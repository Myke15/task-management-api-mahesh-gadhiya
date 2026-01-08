<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

uses(RefreshDatabase::class);

it('load user profile of logged in user', function () {

    $user = User::factory()->create();
    
    $response = $this->actingAs($user)
        ->getJson(route('api.profile'));

    $response->assertStatus(200)
            ->assertJsonStructure([
                'result',
                'user'
            ])->assertJson([
                'user' => [
                    'id' => $user->id
                ]
            ]);
});

it('deny access to user profile for guest user', function () {
    
    $response = $this->getJson(route('api.profile'));

    $response->assertStatus(401)
            ->assertJsonStructure([
                'result',
                'message'
            ])->assertJson([
                'result' => false,
                'message' => 'Unauthenticated.'
            ]);
});