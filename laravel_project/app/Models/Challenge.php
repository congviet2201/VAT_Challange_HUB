<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    protected $table = 'challenges';

    protected $fillable = [
        'category_id',
        'difficulty',
        'title',
        'description',
        'daily_time',
        'image'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
