<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Category - Đại diện cho bảng categories trong database
 *
 * Mô tả: Lưu trữ thông tin các danh mục thử thách
 * Ví dụ: Học tập, Sức khỏe, Phát triển bản thân, v.v.
 */
class Category extends Model
{
    /**
     * Tên bảng trong database
     * Mặc định Laravel sẽ tự động chuyển tên class thành tên bảng (snake_case + s)
     * Nhưng chúng ta khai báo rõ ràng để dễ hiểu
     */
    protected $table = 'categories';

    /**
     * Các trường được phép mass assignment (gán hàng loạt)
     * Chỉ các trường này mới được gán giá trị khi tạo/cập nhật
     */
    protected $fillable = [
        'name',        // Tên danh mục (ví dụ: "Học tập")
        'description', // Mô tả danh mục
        'image'        // Tên file hình ảnh
    ];

    /**
     * Quan hệ với model Challenge
     * Một danh mục có thể có nhiều thử thách
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function challenges()
    {
        return $this->hasMany(Challenge::class);
    }
}
