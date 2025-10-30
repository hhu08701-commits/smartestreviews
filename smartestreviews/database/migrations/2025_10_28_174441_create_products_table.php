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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('brand')->nullable();
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->string('price_text')->nullable(); // e.g., "$29.99"
            $table->decimal('price', 10, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->decimal('rating', 2, 1)->nullable(); // 0.0 to 5.0
            $table->integer('review_count')->default(0);
            $table->string('sku')->nullable();
            $table->string('asin')->nullable(); // Amazon ASIN
            $table->json('specifications')->nullable(); // Technical specs
            $table->json('features')->nullable(); // Key features
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes
            $table->index(['brand', 'is_active']);
            $table->index('rating');
            $table->index('asin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
