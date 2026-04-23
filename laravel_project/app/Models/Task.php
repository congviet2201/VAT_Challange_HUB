<?php
/**
 * File purpose: app/Models/Task.php
 * Chỉ bổ sung chú thích, không thay đổi logic xử lý.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Task chuẩn gắn với một challenge.
 */
class Task extends Model
{
    protected $table = 'tasks';

    protected $fillable = [
        'challenge_id',
        'order',
        'title',
        'description'
    ];

    /**
     * Hàm challenge(): xử lý nghiệp vụ theo tên hàm.
     */
    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    /**
     * Hàm completions(): xử lý nghiệp vụ theo tên hàm.
     */
    public function completions()
    {
        return $this->hasMany(TaskCompletion::class);
    }
}
