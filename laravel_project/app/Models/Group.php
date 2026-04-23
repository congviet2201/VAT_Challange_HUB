<?php
/**
 * File purpose: app/Models/Group.php
 * Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic xá»­ lĂ½.
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
     * HĂ m creator(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * HĂ m users(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'group_user')->withTimestamps();
    }

    /**
     * HĂ m notifications(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * HĂ m challenges(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function challenges()
    {
        return $this->belongsToMany(Challenge::class, 'group_challenge')->withTimestamps();
    }
}
