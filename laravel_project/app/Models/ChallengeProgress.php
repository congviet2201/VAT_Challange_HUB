<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallengeProgress extends Model
{
    protected $table = 'challenge_progress';

  protected $fillable = [
    'user_id',
    'challenge_id',
    'progress',
    'started_at',
    'completed_at',
    'completed_days', 
    'streak'
];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }
}
