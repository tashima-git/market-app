<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    // ---------- 属性 ----------

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ---------- リレーション ----------

    /**
     * プロフィール（1対1）
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * 購入履歴（1対多）
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * お気に入り（多対多）
     */
    public function favoriteItems()
    {
        return $this->belongsToMany(Item::class, 'favorites', 'user_id', 'item_id')->withTimestamps();
    }

    /**
     * 出品商品（1対多）
     */
    public function sales()
    {
        return $this->hasMany(Item::class);
    }

    /**
     * items() エイリアス（必要に応じて）
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
