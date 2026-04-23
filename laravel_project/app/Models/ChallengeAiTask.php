<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model đại diện cho từng task do AI sinh ra trong một ChallengeAiPlan.
 */
class ChallengeAiTask extends Model
{
    protected $table = 'challenge_ai_tasks';

    protected $fillable = [
        'challenge_ai_plan_id',
        'order',
        'title',
        'description',
        'estimated_minutes',
        'due_in_days',
        'completed_at',
        'proof_image_path',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function plan()
    {
        return $this->belongsTo(ChallengeAiPlan::class, 'challenge_ai_plan_id');
    }
}
