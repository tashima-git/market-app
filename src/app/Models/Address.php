<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'postal_code',
        'address',
        'building_name',
    ];

    /**
     * この住所の所有者ユーザー
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
