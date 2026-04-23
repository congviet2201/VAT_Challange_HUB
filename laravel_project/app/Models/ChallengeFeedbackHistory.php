<?php
/**
 * Mục đích file: app/Models/ChallengeFeedbackHistory.php
 * Định nghĩa cấu trúc bảng lưu lịch sử phản hồi AI cho từng user và challenge.
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
}
