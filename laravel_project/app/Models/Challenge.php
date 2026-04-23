<?php
/**
 * File purpose: app/Models/Challenge.php
 * Chá»‰ bá»• sung chĂº thĂ­ch, khĂ´ng thay Ä‘á»•i logic xá»­ lĂ½.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Challenge - Đại diện cho bảng challenges trong database
 *
 * Mô tả: Lưu trữ thông tin các thử thách
 * Ví dụ: "Đọc sách 30 phút mỗi ngày", "Tập thể dục buổi sáng", v.v.
 */
/**
 * Lá»›p Challenge: mĂ´ táº£ vai trĂ² chĂ­nh cá»§a file.
 */
class Challenge extends Model
{
    /**
     * Tên bảng trong database
     */
    protected $table = 'challenges';

    /**
     * Các trường được phép mass assignment
     */
    protected $fillable = [
        'category_id', // ID của danh mục chứa thử thách này
        'difficulty',  // Độ khó: 'easy', 'medium', 'hard'
        'title',       // Tiêu đề thử thách
        'description', // Mô tả chi tiết
        'daily_time',  // Thời gian thực hiện mỗi ngày (phút)
        'image'        // Tên file hình ảnh
    ];

    /**
     * Quan hệ với model Category
     * Một thử thách thuộc về một danh mục
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    /**
     * HĂ m category(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Quan hệ với model ChallengeProgress
     * Một thử thách có thể có nhiều tiến độ của nhiều người dùng
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    /**
     * HĂ m progress(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function progress()
    {
        return $this->hasMany(ChallengeProgress::class);
    }

    /**
     * Quan hệ nhiều-nhiều với model Group
     * Một thử thách có thể được gán cho nhiều nhóm
     * Một nhóm có thể có nhiều thử thách
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    /**
     * HĂ m groups(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_challenge')->withTimestamps();
    }

    /**
     * HĂ m tasks(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * HĂ m aiPlans(): xá»­ lĂ½ nghiá»‡p vá»¥ theo tĂªn hĂ m.
     */
    public function aiPlans()
    {
        return $this->hasMany(ChallengeAiPlan::class);
    }
}
