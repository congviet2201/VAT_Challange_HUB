<?php
/**
 * File purpose: app/Models/ChallengeAiPlan.php
 * Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic xá»­ lĂ½.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model lưu metadata của một kế hoạch AI cá nhân hóa theo user/challenge.
 */
class ChallengeAiPlan extends Model
{
    protected $table = 'challenge_ai_plans';

    protected $fillable = [
        'user_id',
        'challenge_id',
        'source',
        'current_level',
        'summary',
    ];

    /**
     * HĂ m user(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * HĂ m challenge(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    /**
     * HĂ m tasks(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function tasks()
    {
        return $this->hasMany(ChallengeAiTask::class)->orderBy('order');
    }
}
