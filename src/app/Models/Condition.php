<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public $timestamps = false; // マスタなのでタイムスタンプ不要でもOK

    // Itemとのリレーション
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
