<?php
/**
 * File purpose: app/Models/TaskCompletion.php
 * Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic xá»­ lĂ½.
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
     * HĂ m user(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * HĂ m task(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
