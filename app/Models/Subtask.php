<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \App\Models\Task;

class Subtask extends Model
{

/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subtasks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'task_id',
        'title',
        'description',
        'done',
    ];

    /**
     * Get the task the subtask belongs to
     */
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }

}