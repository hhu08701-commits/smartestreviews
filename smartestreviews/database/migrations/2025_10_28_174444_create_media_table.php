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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('path'); // File path
            $table->string('alt')->nullable(); // Alt text for accessibility
            $table->string('caption')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->string('mime_type')->nullable();
            $table->bigInteger('file_size')->nullable(); // In bytes
            $table->string('dominant_color', 7)->nullable(); // Hex color for placeholder
            $table->string('disk')->default('public'); // Storage disk
            $table->json('conversions')->nullable(); // Different sizes (thumb, medium, large)
            $table->morphs('model'); // Polymorphic relationship
            $table->string('collection')->default('default'); // Media collection
            $table->timestamps();
            
            // Indexes
            $table->index('collection');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
