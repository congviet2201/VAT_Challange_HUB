<?php
/**
 * File purpose: app/Models/Group.php
 * Chỉ bổ sung chú thích, không thay đổi logic xử lý.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Group - Đại diện cho nhóm người dùng trong hệ thống
 *
 * Nhóm có thể bao gồm nhiều thành viên và nhiều thử thách.
 */
class Group extends Model
{
    protected $fillable = [
        'name',
        'description',
        'created_by',
        'is_active',
    ];

    /**
     * Hàm creator(): xử lý nghiệp vụ theo tên hàm.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Hàm users(): xử lý nghiệp vụ theo tên hàm.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'group_user')->withTimestamps();
    }

    /**
     * Hàm notifications(): xử lý nghiệp vụ theo tên hàm.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Hàm challenges(): xử lý nghiệp vụ theo tên hàm.
     */
    public function challenges()
    {
        return $this->belongsToMany(Challenge::class, 'group_challenge')->withTimestamps();
    }
}
