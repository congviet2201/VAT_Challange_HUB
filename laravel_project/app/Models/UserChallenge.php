<?php
/**
 * File purpose: app/Models/UserChallenge.php
 * Chỉ bổ sung chú thích, không thay đổi logic xử lý.
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
     * Hàm user(): xử lý nghiệp vụ theo tên hàm.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    /**
     * Hàm challenge(): xử lý nghiệp vụ theo tên hàm.
     */
    public function challenge()
    {
        return $this->belongsTo(Challenge::class, 'challenge_id');
    }
}
