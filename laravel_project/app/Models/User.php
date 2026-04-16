<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Model User - Đại diện cho bảng users trong database
 *
 * Mô tả: Lưu trữ thông tin người dùng của hệ thống
 * Kế thừa từ Authenticatable để có chức năng xác thực (đăng nhập/đăng ký)
 */
class User extends Authenticatable
{
    use HasFactory;

    /**
     * Các trường được phép mass assignment
     */
    protected $fillable = [
        'name',      // Tên đầy đủ của người dùng
        'email',     // Email (duy nhất trong hệ thống)
        'password',  // Mật khẩu (đã được hash)
        'avatar',    // Đường dẫn ảnh đại diện
        'role',      // Vai trò: 'user', 'useradmin', 'admin'
        'is_active', // Trạng thái kích hoạt: 0=không active, 1=active
    ];

    /**
     * Các trường sẽ bị ẩn khi trả về JSON/API
     * Những trường này không được hiển thị ra ngoài
     */
    protected $hidden = [
        'password',       // Không hiển thị mật khẩu
        'remember_token', // Token ghi nhớ đăng nhập
    ];

    /**
     * Quan hệ với model ChallengeProgress
     */
    public function challengeProgress()
    {
        return $this->hasMany(ChallengeProgress::class);
    }

    /**
     * Quan hệ với model UserChallenge
     */
    public function userChallenges()
    {
        return $this->hasMany(UserChallenge::class, 'user_id');
    }

    /**
     * Quan hệ nhiều-nhiều với Group
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user')->withTimestamps();
    }

    /**
     * Quan hệ với Group do người dùng tạo
     */
    public function createdGroups()
    {
        return $this->hasMany(Group::class, 'created_by');
    }

    /**
     * Quan hệ với Notification do người dùng tạo
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'created_by');
    }
}
