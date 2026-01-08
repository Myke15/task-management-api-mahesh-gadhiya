<?php

namespace Database\Factories;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id'    => Project::factory(),
            'title'         => fake()->sentence(),
            'description'   => fake()->paragraph(4),
            'status'        => fake()->randomElement(TaskStatus::cases()),
            'priority'      => fake()->randomElement(TaskPriority::cases()),
            'due_date'      => fake()->dateTimeBetween('now', '+1 months')->format('Y-m-d')
        ];
    }

    /**
     * Indicate that the model's status should be in todo.
     */
    public function todo(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TaskStatus::TODO,
        ]);
    }

    /**
     * Indicate that the model's status should be in progresss.
     */
    public function in_progress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TaskStatus::IN_PROGRESS,
        ]);
    }

    /**
     * Indicate that the model's status should be done.
     */
    public function done(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TaskStatus::DONE,
        ]);
    }

    /**
     * Indicate that the model's priority should be low.
     */
    public function lowPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => TaskPriority::LOW,
        ]);
    }

    /**
     * Indicate that the model's priority should be medium.
     */
    public function mediumPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => TaskPriority::MEDIUM,
        ]);
    }

    /**
     * Indicate that the model's priority should be high.
     */
    public function highPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => TaskPriority::HIGH,
        ]);
    }
}
