<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryItem extends Model
{
    use HasFactory;

    protected $table = 'category_item';

    protected $fillable = [
        'item_id', 
        'category_id'
    ];

    // Item モデルとのリレーション
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Category モデルとのリレーション
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
