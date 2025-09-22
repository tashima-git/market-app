<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');  // いいねしたユーザー
            $table->foreignId('item_id')->constrained('items');  // いいね対象の商品
            $table->timestamps();
            
            $table->unique(['user_id', 'item_id']); // 同じユーザーが同じ商品を重複していいねできないように
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
