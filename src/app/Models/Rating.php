<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'rater_id',
        'rated_id',
        'item_id',
        'rating',
    ];

    // 評価したユーザー
    public function rater()
    {
        return $this->belongsTo(User::class, 'rater_id');
    }

    // 評価されたユーザー
    public function rated()
    {
        return $this->belongsTo(User::class, 'rated_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

}
