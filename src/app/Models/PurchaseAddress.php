<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseAddress extends Model
{
    protected $fillable = [
        'purchase_id',
        'postal_code',
        'address',
        'building_name',
    ];

    // 1:1 ← Purchase
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
