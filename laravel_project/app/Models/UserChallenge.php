<?php

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



    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function challenge()
    {
        return $this->belongsTo(Challenge::class, 'challenge_id');
    }
}
