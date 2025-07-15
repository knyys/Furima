<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'to_user_id', 'image', 'item_id', 'message', 'is_read'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
    
        public function item()
    {
        return $this->belongsTo(Item::class);
    }

    
}
