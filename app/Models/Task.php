<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'status',
        'image',
        'save_as',
        'subtask_id',
        'user_id',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function tasks()
    {
        // return $this->hasMany(Task::class);
        return $this->hasMany(Task::class, 'subtask_id', 'id');
    }
}
