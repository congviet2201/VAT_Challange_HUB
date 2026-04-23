<?php
/**
 * File purpose: app/Models/SubGoal.php
 * Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic xá»­ lĂ½.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Model đại diện cho Mục tiêu phụ (Sub-goal) thuộc về một Goal.
 *
 * Trách nhiệm:
 * - Quản lý quan hệ với Goal và Proof.
 * - Đóng gói logic hoàn thành sub-goal và đồng bộ goal cha.
 */
/**
 * Lá»›p SubGoal: mĂ´ táº£ vai trĂ² chĂ­nh cá»§a file.
 */
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

    /**
     * HĂ m goal(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }

    /**
     * HĂ m proofs(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function proofs()
    {
        return $this->hasMany(SubGoalProof::class);
    }

    /**
     * HĂ m hasProof(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function hasProof()
    {
        return $this->proofs()->exists();
    }

    /**
     * Scope lọc sub-goal theo owner của goal cha.
     */
    public function scopeOwnedByUser(Builder $query, int $userId): Builder
    {
        return $query->whereHas('goal', function (Builder $goalQuery) use ($userId) {
            $goalQuery->where('user_id', $userId);
        });
    }

    /**
     * Kiểm tra trong cùng goal đã có sub-goal hoàn thành trong hôm nay chưa.
     */
    public static function hasCompletedForGoalToday(int $goalId): bool
    {
        return static::query()
            ->where('goal_id', $goalId)
            ->where('status', 'completed')
            ->whereDate('updated_at', now()->toDateString())
            ->exists();
    }

    /**
     * Đánh dấu sub-goal hoàn thành và cập nhật trạng thái goal cha trong transaction.
     */
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
