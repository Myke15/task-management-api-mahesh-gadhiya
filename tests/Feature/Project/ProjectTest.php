<?php

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\ProjectService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('create a new project', function () {

    $user = User::factory()->create();
    $projectData = Project::factory()->make()->makeHidden('user_id')->toArray();

    $response = $this->actingAs($user)
        ->postJson(route('api.projects.store'), $projectData);
    
    $response->assertStatus(200)
        ->assertJsonStructure([
            'result',
            'message'
        ])
        ->assertJson([
            'result'    => true,
            'message'   => 'Project created successfully.'
        ]);

    $this->assertDatabaseHas('projects', [
        'user_id' => $user->id
    ]);
});

it('validate project request', function () {

    $user = User::factory()->create();

    $projectData = [
        'name'      => 'Project'
    ];

    $response = $this->actingAs($user)
        ->postJson(route('api.projects.store'), $projectData);

    $response->assertStatus(422)
            ->assertJsonStructure([
                'result',
                'message',
                'all_failed_validations'
            ]);
});

it('returns 501 in case of unexpected exception for create project api', function () {

    $user = User::factory()->create();

    $projectData = Project::factory()->make()->toArray();

    $this->mock(ProjectService::class, function ($mock) {
        $mock->shouldReceive('createProject')
            ->once()
            ->andThrow(new Exception('Service failure'));
    });
    
    $response = $this->actingAs($user)
        ->postJson(route('api.projects.store'), $projectData);

    $response
        ->assertStatus(501)
        ->assertJson([
            'result'  => false,
            'message' => 'Unable to create project, please try again later!',
        ]);
});

it('show project details', function () {

    $user = User::factory()->create();

    $project = Project::factory()->create([
        'user_id' => $user->id
    ]);
    
    $response = $this->actingAs($user)
        ->getJson(route('api.projects.show', ['project' => $project->id]));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'result',
            'project'
        ])->assertJson([
            'project' => [
                'id' => $project->id,
                'user_id' => $user->id
            ]
        ]);
});

it('can not see other user project', function () {

    $user = User::factory()->create();

    $project = Project::factory()->create([
        'user_id' => $user->id
    ]);

    $otherUser = User::factory()->create();

    $response = $this->actingAs($otherUser)
        ->getJson(route('api.projects.show', ['project' => $project->id]));
    
    $response->assertStatus(404)
        ->assertJsonStructure([
            'result',
            'message'
        ]);
});

it('can update project detail', function () {

    $user = User::factory()->create();

    $project = Project::factory()->create([
        'user_id' => $user->id
    ]);

    $updateData = Project::factory()->make()->makeHidden('user_id')->toArray();

    $response = $this->actingAs($user)
        ->putJson(route('api.projects.update', ['project' => $project->id]), $updateData);
    
    $response->assertStatus(200)
        ->assertJsonStructure([
            'result',
            'message'
        ])
        ->assertJson([
            'result'    => true,
            'message'   => 'Project updated successfully.'
        ]);

    $this->assertDatabaseHas('projects', $updateData);

});

it('can not update project detail', function () {

    $user = User::factory()->create();

    $project = Project::factory()->create([
        'user_id' => $user->id
    ]);

    $otherUser = User::factory()->create();

    $updateData = Project::factory()->make()->makeHidden('user_id')->toArray();

    $response = $this->actingAs($otherUser)
        ->putJson(route('api.projects.update', ['project' => $project->id]), $updateData);
    
    $response->assertStatus(404)
        ->assertJsonStructure([
            'result',
            'message'
        ]);
});


it('can delete own project', function () {

    $user = User::factory()->create();

    $project = Project::factory()
                ->has(Task::factory()->count(2))
                ->create([
                    'user_id' => $user->id
                ]);

    $response = $this->actingAs($user)
        ->deleteJson(route('api.projects.destroy', ['project' => $project->id]));
    
    $response->assertStatus(200)
        ->assertJsonStructure([
            'result',
            'message'
        ])
        ->assertJson([
            'result'    => true,
            'message'   => 'Project removed.'
        ]);

    $this->assertSoftDeleted('projects', [
        'id'            => $project->id
    ]);
    $this->assertSoftDeleted('tasks', [
        'project_id'    => $project->id
    ]);

});

it('can not delete other user project', function () {

    $user = User::factory()->create();

    $project = Project::factory()
                ->has(Task::factory()->count(2))
                ->create([
                    'user_id' => $user->id
                ]);
    $otherUser = User::factory()->create();

    $response = $this->actingAs($otherUser)
        ->deleteJson(route('api.projects.destroy', ['project' => $project->id]));
    
    $response->assertStatus(404)
        ->assertJsonStructure([
            'result',
            'message'
        ]);
});

it('can view project list', function () {

    $user = User::factory()->create();

    Project::factory()->count(5)->create([
        'user_id' => $user->id
    ]);

    $response = $this->actingAs($user)
        ->getJson(route('api.projects.index'));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'result',
            'projects' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'created_at',
                    'updated_at'
                ]
            ],
            'links',
            'meta'
        ]);
});

it('can not view other user projects in list', function () {

    $user = User::factory()->create();

    Project::factory()->count(5)->create([
        'user_id' => $user->id
    ]);

    $otherUser = User::factory()->create();

    $response = $this->actingAs($otherUser)
        ->getJson(route('api.projects.index'));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'result',
            'projects' => [],
            'links',
            'meta'
        ])->assertJson([
            'projects' => []
        ]);
});

it('gives 501 error on unexpected exception for project list api', function () {

    $user = User::factory()->create();

    $this->mock(ProjectService::class, function ($mock) {
        $mock->shouldReceive('listProjects')
            ->once()
            ->andThrow(new Exception('Service failure'));
    });

    $response = $this->actingAs($user)
        ->getJson(route('api.projects.index'));

    $response
        ->assertStatus(501)
        ->assertJson([
            'result'  => false,
            'message' => 'Unable to fetch projects, please try again later!',
        ]);
});

it('gives 404 error when trying to access non existing project', function () {

    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->getJson(route('api.projects.show', ['project' => 9999]));

    $response
        ->assertStatus(404)
        ->assertJson([
            'result'  => false,
            'message' => 'Record not found.',
        ]);
});

it('gives 501 when unexpected exception occurs while updating project', function () {

    $user = User::factory()->create();

    $project = Project::factory()->create([
        'user_id' => $user->id
    ]);

    $updateData = Project::factory()->make()->makeHidden('user_id')->toArray();

    $this->mock(ProjectService::class, function ($mock) use ($project, $updateData) {
        $mock->shouldReceive('updateProject')
            ->once()
            ->andThrow(new Exception('Service failure'));
    });

    $response = $this->actingAs($user)
        ->putJson(route('api.projects.update', ['project' => $project->id]), $updateData);
    
    $response
        ->assertStatus(501)
        ->assertJson([
            'result'  => false,
            'message' => 'Unable to update project, please try again later!',
        ]);
});
