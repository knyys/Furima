<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'address_number',
        'address',
        'building',
    ];

    // User モデルとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Item モデルとのリレーション
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
