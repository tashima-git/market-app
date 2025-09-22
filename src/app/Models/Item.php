<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'brand',
        'description',
        'price',
        'status',
    ];

    // N:1 ← User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // N:1 ← Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // 1:1 → ItemImage
    public function image()
    {
        return $this->hasOne(ItemImage::class);
    }

    // 1:1 → Purchase
    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }

    // 1:N → Favorite
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // 1:N → Comment
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
