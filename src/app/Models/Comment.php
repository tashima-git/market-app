<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'item_id',
        'comment',
    ];

    // N:1 ← User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // N:1 ← Item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
