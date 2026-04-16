<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    protected $table = 'challenges';

    protected $fillable = [
        'category_id',
        'difficulty',
        'title',
        'description',
        'daily_time',
        'image'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function progress()
    {
        return $this->hasMany(ChallengeProgress::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_challenge')->withTimestamps();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
