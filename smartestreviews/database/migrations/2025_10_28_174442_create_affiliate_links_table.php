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
        Schema::create('affiliate_links', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // For cloaking URL like /go/{slug}
            $table->string('label'); // Display text for the link
            $table->text('url'); // Actual affiliate URL
            $table->string('merchant'); // Amazon, Walmart, etc.
            $table->string('rel')->default('sponsored nofollow'); // Link attributes
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('post_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('enabled')->default(true);
            $table->integer('clicks_count')->default(0);
            $table->timestamp('last_clicked_at')->nullable();
            $table->json('utm_params')->nullable(); // UTM tracking parameters
            $table->timestamps();
            
            // Indexes
            $table->index(['merchant', 'enabled']);
            $table->index(['post_id', 'enabled']);
            $table->index('clicks_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_links');
    }
};
