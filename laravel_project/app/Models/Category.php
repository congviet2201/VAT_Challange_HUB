<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = ['name', 'description', 'image'];

    // 🔥 Quan trọng nhất
    public function challenges()
    {
        return $this->hasMany(Challenge::class);
    }
}
