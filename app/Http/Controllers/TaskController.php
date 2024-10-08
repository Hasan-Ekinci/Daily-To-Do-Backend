<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Task;
use App\Models\Subtask;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

class TaskController extends Controller
{
    /**
     * return the tasks that belong to the user
     */
    public function index(int $userId, string $type = null)
    {
        $tasks = Task::query()
            ->where('user_id', $userId)
            ->with('subtasks')
            ->withCount([
                'subtasks',
                'subtasks as done_sub_tasks_count' => function (Builder $query) {
                    $query->where('done', 1);
                },
            ]);


        if (!empty($type) && $type === 'active') {
            $tasks->where('done', 0)
                ->where('archived', 0);

        } else if (!empty($type)) {
            $tasks->where($type, 1);
        }

        return $tasks->get();
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

    /**
     * Edit the title or description of a task or sub-task
     */
    public function editField(\Illuminate\Http\Request $request)
    {
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

    /**
     * set done, archive or delet a (sub)task
     */
    public function taskAction(\Illuminate\Http\Request $request)
    {
        // id
        // isSubtask
        // action = done || archive || delete
        // newValue = true || false

        // get the correct model
        if ($request->isSubtask) {
            $entity = Subtask::query()
                ->where('id', $request->id)
                ->first();
        } else {
            $entity = Task::query()
                ->where('id', $request->id)
                ->first();
        }

        // do the correct action
        if ($request->action === 'delete') {
            Subtask::query()
                ->where('task_id', $entity->id)
                ->delete();

            $entity->delete();

        } else if ($request->action === 'done') {
            $entity->update([
                'done' => $request->newValue,
                'archived' => 0
            ]);

            // if a task is set to done, set all sub-tasks to done as well
            // if (!$request->isSubtask)
            //     Subtask::query()
            //         ->where('task_id', $entity->id)
            //         ->update([
            //             'done' => 1
            //         ]);

        } else if ($request->action === 'archive') {
            $entity->update([
                'archived' => $request->newValue,
                'done' => 0
            ]);
        }

        return response()->json([
            'status' => 'success'
        ], 201);
    }

    /**
     * Add a subtask
     */
    public function addSubtask(\Illuminate\Http\Request $request)
    {
        // task id
        Subtask::query()
            ->create([
                'task_id' => $request->taskId,
                'title' => 'NIEUWE SUBTAAK'
            ]);

        return response()->json([
            'status' => 'success'
        ], 201);
    }
}