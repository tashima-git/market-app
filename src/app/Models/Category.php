<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
    ];

    // 1:N → Item
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
