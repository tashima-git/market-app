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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); // 外部キー
            $table->string('username');       // ユーザー名（表示用）
            $table->string('avatar')->nullable(); // プロフィール画像
            $table->string('postal_code');    // 郵便番号
            $table->string('address');        // 住所
            $table->string('building_name')->nullable(); // 建物名
            $table->timestamps();             // created_at / updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
