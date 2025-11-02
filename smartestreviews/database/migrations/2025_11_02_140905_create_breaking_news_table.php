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
        Schema::create('breaking_news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('link')->nullable(); // URL hoặc route
            $table->string('image_url')->nullable(); // Hình ảnh hiển thị
            $table->unsignedBigInteger('post_id')->nullable(); // Link đến post cụ thể
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('set null');
            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breaking_news');
    }
};
