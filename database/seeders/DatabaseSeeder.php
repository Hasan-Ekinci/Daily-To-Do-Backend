<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $user = \App\Models\User::factory()->create(
            [
                'name' => 'Hasan',
                'email' => 'hasan@email.com',
            ]
        );

        $task = \App\Models\Task::create(
            [
                'user_id' => $user->id,
                'title' => 'De eerste taak',
                'description' => 'Beschrijving van de taak',
            ]
        );

        \App\Models\Subtask::create(
            [
                'task_id' => $user->id,
                'title' => 'De subtaak',
                'description' => 'Beschrijving van de subtaak',
            ]
        );
    }
}
