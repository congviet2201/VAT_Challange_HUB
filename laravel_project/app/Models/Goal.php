<?php
/**
 * File purpose: app/Models/Goal.php
 * Chỉ bổ sung chú thích, không thay đổi logic xử lý.
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

/**
 * Model Goal (mục tiêu chính) của người dùng.
 *
 * Chứa metadata của mục tiêu và quan hệ 1-n với SubGoal.
 */
class Goal extends Model
{
 protected $fillable = [
    'user_id',
    'title',
    'category_id',
    'description',
    'duration_days',
    'status',
    'last_completed_date'
];

protected $casts = [
    'duration_days' => 'integer',
    'status' => 'string',
];

protected $table = 'goals';

/**
 * Hàm user(): xử lý nghiệp vụ theo tên hàm.
 */
public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
/**
 * Hàm category(): xử lý nghiệp vụ theo tên hàm.
 */
public function category()
{
    return $this->belongsTo(Category::class, 'category_id');
}

/**
 * Hàm subGoals(): xử lý nghiệp vụ theo tên hàm.
 */
public function subGoals()
{
    return $this->hasMany(SubGoal::class);
}

/**
 * Kiểm tra goal đã hoàn thành hay chưa dựa vào cột status.
 */
public function isCompleted()
{
    if (! Schema::hasColumn('goals', 'status')) {
        return false;
    }

    return $this->status === 'completed';
}

/**
 * Đồng bộ trạng thái goal dựa trên tỷ lệ sub-goal completed.
 */
public function checkCompletion()
{
    if (! Schema::hasColumn('goals', 'status')) {
        return false;
    }

    $totalSubGoals = $this->subGoals()->count();
    $completedSubGoals = $this->subGoals()->where('status', 'completed')->count();

    if ($totalSubGoals > 0 && $totalSubGoals === $completedSubGoals) {
        $this->update(['status' => 'completed']);
        return true;
    }

    return false;
}
}
