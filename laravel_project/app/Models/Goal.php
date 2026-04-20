<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
 protected $fillable = [
    'user_id',
    'title',
    'category_id',
    'description'
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
}