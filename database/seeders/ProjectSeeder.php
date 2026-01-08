<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;;
use App\Models\User;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        //Create pending projects
        Project::factory()->count(5)->pending()->create([
            'user_id' => $user->id
        ]);

        //Create IN_PROGRESS projects
        Project::factory()->count(5)->IN_PROGRESS()->create([
            'user_id' => $user->id
        ]);
    }
}
