<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'item_id',
        'user_id',
        'payment_method',
        'purchased_at',
    ];

    // 1:1 ← Item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // N:1 ← User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 1:1 → PurchaseAddress
    public function address()
    {
        return $this->hasOne(PurchaseAddress::class);
    }
}
