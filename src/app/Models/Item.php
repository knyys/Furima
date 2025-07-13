<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'price', 'image', 'detail', 'condition_id', 'user_id', 'sold', 'brand'
    ];

    protected $casts = [
        'price' => 'integer',
    ];

    //ローカルスコープ（検索）
    public function scopeNameSearch($query, $keyword)
    {
        if ($keyword) {
        return $query->where('name', 'like', '%' . $keyword . '%');
        }
        
        return $query;
    }


    //リレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function isLikedByUser($user)
    {
        if (!$user) {
            return false;
        }
        return $this->likes->where('user_id', $user->id)->isNotEmpty();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item');
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    public function solds()
    {
        return $this->hasMany(Sold::class);
    }

}

