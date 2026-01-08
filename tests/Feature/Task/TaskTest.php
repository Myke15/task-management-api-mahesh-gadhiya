<?php

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('create a new task under a project', function () {

    $user = User::factory()->create();
    $project = Project::factory()->for($user)->create();
    $taskData = Task::factory()->make([
        'title'         => 'New Task',
        'description'   => 'Task description',
        'status'        => TaskStatus::TODO,
        'priority'      => TaskPriority::MEDIUM
    ])->toArray();

    $response = $this->actingAs($user)
        ->postJson(route('api.projects.tasks.store', ['project' => $project->id]), $taskData);
    
    $response->assertStatus(200)
        ->assertJsonStructure([
            'result',
            'message'
        ])
        ->assertJson([
            'result'    => true,
            'message'   => 'Task created successfully!'
        ]);

    $this->assertDatabaseHas('tasks', [
        'project_id'    => $project->id,
        'title'         => 'New Task'
    ]);
});

it('validate task creation request', function () {

    $user = User::factory()->create();
    $project = Project::factory()->for($user)->create();

    $taskData = [
        'title' => 'Task without description and status'
    ];

    $response = $this->actingAs($user)
        ->postJson(route('api.projects.tasks.store', ['project' => $project->id]), $taskData);

    $response->assertStatus(422)
            ->assertJsonStructure([
                'result',
                'message',
                'all_failed_validations'
            ]);
});

it('returns 501 in case of unexpected exception for create task api', function () {

    $user = User::factory()->create();
    $project = Project::factory()->for($user)->create();
    $taskData = Task::factory()->make([
        'title'         => 'New Task',
        'description'   => 'Task description',
        'status'        => TaskStatus::TODO,
        'priority'      => TaskPriority::MEDIUM
    ])->toArray();

    $this->mock(TaskService::class, function ($mock) {
        $mock->shouldReceive('createTask')
            ->once()
            ->andThrow(new \Exception("Unexpected error"));
    });

    $response = $this->actingAs($user)
        ->postJson(route('api.projects.tasks.store', ['project' => $project->id]), $taskData);

    $response->assertStatus(501)
            ->assertJsonStructure([
                'result',
                'message'
            ])->assertJson([
                'result'    => false,
                'message'   => 'Unable to create task, please try again later!'
            ]);
});

it('can show task details', function () {

    $user = User::factory()->create();
    $project = Project::factory()->for($user)->create();
    $task = Task::factory()->for($project)->create();

    $response = $this->actingAs($user)
        ->getJson(route('api.tasks.show', ['task' => $task->id]));

    $response->assertStatus(200)
            ->assertJsonStructure([
                'task' => [
                    'id',
                    'project_id',
                    'title',
                    'description',
                    'status',
                    'priority',
                    'due_date',
                    'created_at',
                    'updated_at'
                ],
                'result'
            ])->assertJson([
                'task' => [
                    'id' => $task->id,
                    'project_id' => $project->id
                ]
            ]);
});

it('can update task details', function () {

    $user = User::factory()->create();
    $project = Project::factory()->for($user)->create();
    $task = Task::factory()->for($project)->create([
        'title' => 'Old Task Title',
        'description' => 'Old Task Description',
        'status' => TaskStatus::TODO,
        'priority' => TaskPriority::LOW
    ]);

    $updateData = [
        'title'         => 'Updated Task Title',
        'description'   => 'Updated Task Description',
        'status'        => TaskStatus::IN_PROGRESS,
        'priority'      => TaskPriority::HIGH
    ];

    $response = $this->actingAs($user)
        ->putJson(route('api.tasks.update', ['task' => $task->id]), $updateData);

    $response->assertStatus(200)
            ->assertJsonStructure([
                'result',
                'message'
            ])->assertJson([
                'result'    => true,
                'message'   => 'Task updated successfully!'
            ]);

    $this->assertDatabaseHas('tasks', [
        'id'            => $task->id,
        'title'         => 'Updated Task Title',
        'description'   => 'Updated Task Description',
        'status'        => TaskStatus::IN_PROGRESS,
        'priority'      => TaskPriority::HIGH
    ]);
});

it('can delete a task', function () {

    $user = User::factory()->create();
    $project = Project::factory()->for($user)->create();
    $task = Task::factory()->for($project)->create();

    $response = $this->actingAs($user)
        ->deleteJson(route('api.tasks.destroy', ['task' => $task->id]));

    $response->assertStatus(200)
            ->assertJsonStructure([
                'result',
                'message'
            ])->assertJson([
                'result'    => true,
                'message'   => 'Task removed!'
            ]);

    $this->assertSoftDeleted('tasks', [
        'id' => $task->id
    ]);
});

it('can list all tasks under a project', function () {

    $user = User::factory()->create();
    $project = Project::factory()->for($user)->create();
    $tasks = Task::factory()->count(3)->for($project)->create();

    $response = $this->actingAs($user)
        ->getJson(route('api.projects.tasks.index', ['project' => $project->id]));

    $response->assertStatus(200)
            ->assertJsonStructure([
                'result',
                'tasks' => [
                    '*' => [
                        'id',
                        'project_id',
                        'title',
                        'description',
                        'status',
                        'priority',
                        'due_date',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ])->assertJsonCount(3, 'tasks');
});