<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Goal extends Model
{
 protected $fillable = [
    'user_id',
    'title',
    'category_id',
    'description',
    'status',
    'last_completed_date'
];

protected $casts = [
    'status' => 'string',
];

protected $table = 'goals';

public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
public function category()
{
    return $this->belongsTo(Category::class, 'category_id');
}

public function subGoals()
{
    return $this->hasMany(SubGoal::class);
}

public function isCompleted()
{
    if (! Schema::hasColumn('goals', 'status')) {
        return false;
    }

    return $this->status === 'completed';
}

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
