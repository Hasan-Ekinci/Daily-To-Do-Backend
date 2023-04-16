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
            ->first();
    }
}