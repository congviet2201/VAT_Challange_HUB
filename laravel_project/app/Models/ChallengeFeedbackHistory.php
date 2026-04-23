<?php
/**
 * File purpose: app/Models/ChallengeFeedbackHistory.php
 * Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic xá»­ lĂ½.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model lưu lịch sử feedback AI theo từng user/challenge.
 */
class ChallengeFeedbackHistory extends Model
{
    use HasFactory;

    protected $table = 'challenge_feedback_histories';

    protected $fillable = [
        'user_id',
        'challenge_id',
        'source',
        'evaluation',
        'suggestions',
    ];

    protected $casts = [
        'suggestions' => 'array',
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
}
