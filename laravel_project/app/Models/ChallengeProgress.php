<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model ChallengeProgress - Lưu trữ tiến độ thử thách của người dùng
 *
 * Mỗi bản ghi nói lên tiến độ của người dùng với một challenge cụ thể.
 */
class ChallengeProgress extends Model
{
    protected $table = 'challenge_progress';

    protected $fillable = [
        'user_id',
        'challenge_id',
        'progress',
        'started_at',
        'completed_at',
        'completed_days',
        'streak',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }
}
