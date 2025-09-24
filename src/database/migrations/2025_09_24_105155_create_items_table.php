<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('condition_id')->constrained('conditions')->onDelete('cascade');
            $table->string('name');
            $table->string('brand')->nullable();
            $table->text('description')->nullable();
            $table->integer('price');
            $table->string('status');
            $table->string('path', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
