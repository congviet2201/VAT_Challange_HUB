<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    protected $table = 'checkins';

    protected $fillable = [
        'user_id',
        'challenge_id',
        'date',
        'status'
    ];
}