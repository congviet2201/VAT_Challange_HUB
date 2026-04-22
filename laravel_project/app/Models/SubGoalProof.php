<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubGoalProof extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'sub_goal_id',
        'type',
        'content',
    ];

    public function subGoal()
    {
        return $this->belongsTo(SubGoal::class);
    }
}
