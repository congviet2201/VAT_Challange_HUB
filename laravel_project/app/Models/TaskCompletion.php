<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model lưu mốc hoàn thành task chuẩn theo từng user.
 */
class TaskCompletion extends Model
{
    protected $table = 'task_completions';

    protected $fillable = [
        'user_id',
        'task_id',
        'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
