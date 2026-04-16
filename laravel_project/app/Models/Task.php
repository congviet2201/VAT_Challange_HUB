<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';

    protected $fillable = [
        'challenge_id',
        'order',
        'title',
        'description'
    ];

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    public function completions()
    {
        return $this->hasMany(TaskCompletion::class);
    }
}
