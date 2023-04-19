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

    /**
     * Get the task for the show page
     */
    public function show(int $userId, int $taskId)
    {
        return Task::query()
            ->where('id', $taskId)
            ->where('user_id', $userId)
            ->with('subtasks')
            ->first();
    }

    public function editField(\Illuminate\Http\Request $request)
    {
        // uit request kijgen, task of subtask id, isSubtask, isTitle, newValue

        if (!$request->isSubtask) {
            Task::query()
                ->where('id', $request->id)
                ->update([
                    $request->isTitle ? 'title' : 'description' => $request->newValue
                ]);

            return response()->json([
                'status' => 'success'
            ], 201);

        } else if ($request->isSubtask) {
            Subtask::query()
                ->where('id', $request->id)
                ->update([
                    $request->isTitle ? 'title' : 'description' => $request->newValue
                ]);

            return response()->json([
                'status' => 'success'
            ], 201);
        }

        return response()->json([], 404);
    }
}