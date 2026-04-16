<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasFactory;
<<<<<<< HEAD


    protected $table = 'users';
=======
>>>>>>> origin/feature/challenge

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'role',
<<<<<<< HEAD
        'is_active',
=======
        'is_active'
>>>>>>> origin/feature/challenge
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];


    public function challengeProgress()
    {
        return $this->hasMany(ChallengeProgress::class);
    }

    public function userChallenges()
    {
        return $this->hasMany(UserChallenge::class, 'user_id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user')->withTimestamps();
    }

    public function createdGroups()
    {
        return $this->hasMany(Group::class, 'created_by');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'created_by');
    }
}
<<<<<<< HEAD
}
=======
>>>>>>> origin/feature/challenge
