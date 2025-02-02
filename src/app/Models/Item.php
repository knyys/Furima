<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'detail', 
        'price', 
        'image'
    ];

    protected $casts = [
        'price' => 'integer',
    ];


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

    public function getlikesCountAttribute()
    {
        return $this->likes()->count();
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function conditions()
    {
        return $this->hasMany(Condition::class);
    }

    public function sold()
    {
        return $this->hasOne(Sold::class);
    }
}
