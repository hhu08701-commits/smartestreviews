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
        Schema::create('list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->integer('rank'); // Position in the list (1, 2, 3, etc.)
            $table->string('product_name');
            $table->string('brand')->nullable();
            $table->text('verdict')->nullable(); // Short description/verdict
            $table->decimal('rating', 2, 1)->nullable(); // 0.0 to 5.0
            $table->string('price_text')->nullable();
            $table->string('image_url')->nullable();
            $table->foreignId('affiliate_link_id')->nullable()->constrained()->onDelete('set null');
            $table->json('pros')->nullable(); // Array of pros
            $table->json('cons')->nullable(); // Array of cons
            $table->json('specifications')->nullable(); // Technical specs
            $table->boolean('is_featured')->default(false); // Highlight this item
            $table->timestamps();
            
            // Indexes
            $table->index(['post_id', 'rank']);
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_items');
    }
};
