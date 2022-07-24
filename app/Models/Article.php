<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'image',
        'user_id',
        'category_id',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['user_id'] ?? false, function($query, $user_id) {
            return $query->whereHas('user_id', function($query) use ($user_id) {
                $query->where('user_id', $user_id);
            });
        });
    }

    public function category() 
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function user_id()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

