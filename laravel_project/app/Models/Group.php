<?php

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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'group_user')->withTimestamps();
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function challenges()
    {
        return $this->belongsToMany(Challenge::class, 'group_challenge')->withTimestamps();
    }
}
