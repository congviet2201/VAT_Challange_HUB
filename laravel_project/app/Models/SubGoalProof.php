<?php
/**
 * File purpose: app/Models/SubGoalProof.php
 * Chỉ bổ sung chú thích, không thay đổi logic xử lý.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Lớp SubGoalProof: mô tả vai trò chính của file.
 */
class SubGoalProof extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'sub_goal_id',
        'type',
        'content',
    ];

    /**
     * Hàm subGoal(): xử lý nghiệp vụ theo tên hàm.
     */
    public function subGoal()
    {
        return $this->belongsTo(SubGoal::class);
    }
}
