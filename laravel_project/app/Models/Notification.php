<?php
/**
 * File purpose: app/Models/Notification.php
 * Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic xá»­ lĂ½.
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
     * HĂ m group(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * HĂ m creator(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
