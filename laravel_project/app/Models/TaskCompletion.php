<?php
/**
 * File purpose: app/Models/TaskCompletion.php
 * Chỉ bổ sung chú thích, không thay đổi logic xử lý.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model lưu mốc hoàn thành task chuẩn theo từng user.
 */
class TaskCompletion extends Model
{
    protected $table = 'task_completions';

    protected $fillable = [
        'user_id',
        'task_id',
        'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime'
    ];

    /**
     * Hàm user(): xử lý nghiệp vụ theo tên hàm.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Hàm task(): xử lý nghiệp vụ theo tên hàm.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
