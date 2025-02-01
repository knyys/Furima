<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sold extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'sold'
    ];

    //リレーション
     public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
