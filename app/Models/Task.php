<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \App\Models\Subtask;
use \App\Models\User;

class Task extends Model
{

/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tasks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'done',
        'archived',
    ];

    /**
     * Get the subtasks that belong to the task
     */
    public function subtasks()
    {
        return $this->hasMany(Subtask::class, 'task_id', 'id');
    }

    /**
     * Get the user the task belongs to
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}