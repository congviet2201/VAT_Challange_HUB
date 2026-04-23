<?php
/**
 * Mục đích file: app/Models/ChallengeAiPlan.php
 * Định nghĩa cấu trúc bảng lưu kế hoạch AI cá nhân hóa cho từng user và challenge.
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
     * Hàm user(): xử lý nghiệp vụ theo tên hàm.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Hàm challenge(): xử lý nghiệp vụ theo tên hàm.
     */
    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    /**
     * Hàm tasks(): xử lý nghiệp vụ theo tên hàm.
     */
    public function tasks()
    {
        return $this->hasMany(ChallengeAiTask::class)->orderBy('order');
    }
}
