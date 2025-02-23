<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sold extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'user_id', 
        'item_id',
        'sold',
        'method'
    ];

    //リレーション
     public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
