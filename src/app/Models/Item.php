<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'condition_id',
        'name',
        'brand',
        'description',
        'price',
        'status',
        'path',
    ];

    // ---------- リレーション ----------

    // 出品者（1対1）
    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 購入履歴（1対1 / Purchase モデル経由）
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // お気に入り登録（多対多）
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    // 商品のカテゴリ（多対多）
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_items')->withTimestamps();
    }
}
