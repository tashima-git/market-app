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

    // 出品者（User）とのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 商品の状態
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    // カテゴリー（多対多）
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_items');
    }

    // お気に入りしたユーザー
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    // コメント
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function purchases()
{
    return $this->hasMany(Purchase::class);
}

// 購入済み判定用メソッド（任意）
public function isSold(): bool
{
    return $this->purchases()->exists();
}
}
