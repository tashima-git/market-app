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
        Schema::create('purchase_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases'); // 購入に紐づく
            $table->string('postal_code');      // 郵便番号
            $table->string('address');          // 住所
            $table->string('building_name')->nullable(); // 建物名
            $table->timestamps();               // created_at / updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_addresses');
    }
};
