<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserChallenge extends Model
{
    protected $table = 'user_challenges';

    protected $fillable = [
        'user_id',
        'challenge_id',
        'progress',
        'completed_days',
        'streak'
    ];
}