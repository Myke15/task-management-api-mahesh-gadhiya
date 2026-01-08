<?php

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'       => User::factory(),
            'name'          => fake()->sentence(3),
            'description'   => fake()->paragraph(),
            'status'        => fake()->randomElement(ProjectStatus::cases())
        ];
    }

    /**
     * Indicate that the model's status should be pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProjectStatus::PENDING,
        ]);
    }

    /**
     * Indicate that the model's status should be in progresss.
     */
    public function IN_PROGRESS(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProjectStatus::IN_PROGRESS,
        ]);
    }

    /**
     * Indicate that the model's status should be completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProjectStatus::COMPLETED,
        ]);
    }
}
