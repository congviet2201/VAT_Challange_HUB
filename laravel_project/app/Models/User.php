<?php
/**
 * File purpose: app/Models/User.php
 * Chỉ bổ sung chú thích, không thay đổi logic xử lý.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


/**
 * Lớp User: mô tả vai trò chính của file.
 */
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
    /**
     * Hàm challengeProgress(): xử lý nghiệp vụ theo tên hàm.
     */
    public function challengeProgress()
    {
        return $this->hasMany(ChallengeProgress::class);
    }

    /**
     * Hàm userChallenges(): xử lý nghiệp vụ theo tên hàm.
     */
    public function userChallenges()
    {
        return $this->hasMany(UserChallenge::class, 'user_id');
    }

    /**
     * Hàm groups(): xử lý nghiệp vụ theo tên hàm.
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user')->withTimestamps();
    }

    /**
     * Hàm createdGroups(): xử lý nghiệp vụ theo tên hàm.
     */
    public function createdGroups()
    {
        return $this->hasMany(Group::class, 'created_by');
    }

    /**
     * Hàm notifications(): xử lý nghiệp vụ theo tên hàm.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'created_by');
    }
}
