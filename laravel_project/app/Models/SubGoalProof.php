<?php
/**
 * File purpose: app/Models/SubGoalProof.php
 * Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic xá»­ lĂ½.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Lá»›p SubGoalProof: mĂ´ táº£ vai trĂ² chĂ­nh cá»§a file.
 */
class SubGoalProof extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'sub_goal_id',
        'type',
        'content',
    ];

    /**
     * HĂ m subGoal(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function subGoal()
    {
        return $this->belongsTo(SubGoal::class);
    }
}
