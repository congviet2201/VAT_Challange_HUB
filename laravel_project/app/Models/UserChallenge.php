<?php
/**
 * File purpose: app/Models/UserChallenge.php
 * Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic xá»­ lĂ½.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model UserChallenge - Lưu trữ thử thách mà người dùng đã tham gia
 *
 * Mỗi bản ghi là một thử thách đang được theo dõi bởi một người dùng.
 */
class UserChallenge extends Model
{
    protected $table = 'user_challenges';

    protected $fillable = [
        'user_id',
        'challenge_id',
        'progress',
        'completed_days',
        'streak',
    ];



    /**
     * HĂ m user(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    /**
     * HĂ m challenge(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function challenge()
    {
        return $this->belongsTo(Challenge::class, 'challenge_id');
    }
}
