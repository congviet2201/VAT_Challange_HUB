<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'role',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function challengeProgress()
    {
        return $this->hasMany(ChallengeProgress::class);
    }

    public function userChallenges()
    {
        return $this->hasMany(UserChallenge::class, 'user_id');
    }
}
