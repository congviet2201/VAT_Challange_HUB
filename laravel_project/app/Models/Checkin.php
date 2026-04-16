<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Checkin - Lưu trữ lịch sử check-in hàng ngày cho từng thử thách
 *
 * Mỗi ngày người dùng check-in sẽ tạo một bản ghi mới.
 */
class Checkin extends Model
{
    protected $table = 'checkins';

    protected $fillable = [
        'user_id',
        'challenge_id',
        'date',
        'status',
    ];
}
