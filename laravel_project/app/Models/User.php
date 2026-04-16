<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasFactory;


    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'role',
        'is_active',
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
    // app/Models/User.php

public function groups()
{
    // Một người dùng có thể tham gia nhiều nhóm thông qua bảng trung gian group_members
    return $this->belongsToMany(Group::class, 'group_members', 'user_id', 'group_id');
}
// app/Models/User.php

public function userChallenges()
{
    return $this->hasMany(UserChallenge::class, 'user_id');
}
}
