<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SubGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'goal_id',
        'title',
        'description',
        'day',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }

    public function proofs()
    {
        return $this->hasMany(SubGoalProof::class);
    }

    public function hasProof()
    {
        return $this->proofs()->exists();
    }

    public function scopeOwnedByUser(Builder $query, int $userId): Builder
    {
        return $query->whereHas('goal', function (Builder $goalQuery) use ($userId) {
            $goalQuery->where('user_id', $userId);
        });
    }

    public static function hasCompletedForGoalToday(int $goalId): bool
    {
        return static::query()
            ->where('goal_id', $goalId)
            ->where('status', 'completed')
            ->whereDate('updated_at', now()->toDateString())
            ->exists();
    }

    public function completeAndSyncGoal(): Goal
    {
        return DB::transaction(function () {
            $this->update([
                'status' => 'completed',
            ]);

            $goal = $this->goal()->lockForUpdate()->firstOrFail();
            $hasPendingSubGoal = $goal->subGoals()->where('status', '!=', 'completed')->exists();

            $goalDataToUpdate = [
                'last_completed_date' => now()->toDateString(),
            ];

            if (! $hasPendingSubGoal) {
                $goalDataToUpdate['status'] = 'completed';
            }

            $goal->update($goalDataToUpdate);

            return $goal->fresh();
        });
    }
}
