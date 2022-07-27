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

    protected $with = [
        'category',
        'user'
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['user_id'] ?? false, function($query, $user) {
            return $query->whereHas('user', function($query) use ($user) {
                $query->where('user_id', $user);
            });
        });
    }

    public function category() 
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

