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
        Schema::create('product_showcases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->string('product_name');
            $table->string('brand')->nullable();
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->string('price_text')->nullable(); // e.g., "$29.99"
            $table->decimal('price', 10, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->decimal('rating', 2, 1)->nullable(); // 0.0 to 5.0
            $table->integer('review_count')->default(0);
            $table->string('affiliate_url'); // Link affiliate
            $table->string('affiliate_label')->nullable(); // Text hiển thị trên button
            $table->string('merchant')->nullable(); // Amazon, eBay, etc.
            $table->json('features')->nullable(); // Array of features
            $table->json('pros')->nullable(); // Array of pros
            $table->json('cons')->nullable(); // Array of cons
            $table->integer('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes
            $table->index(['post_id', 'sort_order']);
            $table->index(['is_active', 'is_featured']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_showcases');
    }
};
