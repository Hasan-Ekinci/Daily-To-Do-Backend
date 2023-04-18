<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Task;
use App\Models\Subtask;
use Illuminate\Support\Facades\Hash;

class TaskController extends Controller
{
    /**
     * return the tasks that belong to the user
     */
    public function index(int $userId)
    {
        return Task::query()
            ->where('user_id', $userId)
            ->with('subtasks')
            ->get();
    }

    /**
     * Add a task for the user
     */
    public function addTask(\Illuminate\Http\Request $request)
    {
        // Add the new task to the database
        $task = Task::query()
            ->create([
                'user_id' => $request->userId,
                'title' => $request->title,
                'description' => $request->description
            ]);

        // Add all the subtasks
        foreach ($request->subTasks as $subtask) {
            Subtask::query()
                ->create([
                    'task_id' => $task->id,
                    'title' => $subtask['title'],
                    'description' => $subtask['description']
                ]);
        }

        // return a success message
        return response()->json([
            'status' => 'success'
        ], 201);
    }
}