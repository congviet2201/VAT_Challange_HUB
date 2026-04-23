<?php
/**
 * File purpose: app/Models/Task.php
 * Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic xá»­ lĂ½.
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
     * HĂ m challenge(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    /**
     * HĂ m completions(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function completions()
    {
        return $this->hasMany(TaskCompletion::class);
    }
}
