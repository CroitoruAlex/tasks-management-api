<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admins = User::factory(3)->create([
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $managers = User::factory(3)->create([
            'role' => 'manager',
            'password' => bcrypt('password'),
        ]);

        $users = User::factory(5)->create([
            'role' => 'user',
            'password' => bcrypt('password'),
        ]);

        $projects = Project::factory(5)->make()->each(function ($project) use ($admins) {
            $project->created_by = $admins->random()->id;
            $project->save();
        });

        $tasks = collect();
        foreach ($managers as $manager) {
            $managerTasks = Task::factory(ceil(10 / $managers->count()))->make()->each(function ($task) use ($projects, $users) {
                $task->project_id = $projects->random()->id;
                $task->assigned_to = $users->random()->id;
                $task->save();
            });

            $tasks = $tasks->merge($managerTasks);
        }

        foreach ($users as $user) {
            Comment::factory(2)->make()->each(function ($comment) use ($tasks, $user) {
                $comment->task_id = $tasks->random()->id;
                $comment->user_id = $user->id;
                $comment->save();
            });
        }

        $this->command->info('✅ Database seeded:
        - 3 admins
        - 3 managers
        - 5 users
        - 5 projects
        - 10 tasks
        - 10 comments
        ');
    }
}
