<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'username',
        'avatar',
        'postal_code',
        'address',
        'building_name',
    ];

    // 1:1 ← User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
