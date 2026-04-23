<?php

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }
}
