<?php

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    public function tasks()
    {
        return $this->hasMany(ChallengeAiTask::class)->orderBy('order');
    }
}
