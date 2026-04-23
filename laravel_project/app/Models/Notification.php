<?php
/**
 * File purpose: app/Models/Notification.php
 * Chỉ bổ sung chú thích, không thay đổi logic xử lý.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Notification - Lưu trữ thông báo gửi tới thành viên nhóm
 *
 * Các thông báo do UserAdmin tạo và gửi tới các nhóm.
 */
class Notification extends Model
{
    protected $fillable = [
        'group_id',
        'created_by',
        'title',
        'message',
    ];

    /**
     * Hàm group(): xử lý nghiệp vụ theo tên hàm.
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Hàm creator(): xử lý nghiệp vụ theo tên hàm.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
