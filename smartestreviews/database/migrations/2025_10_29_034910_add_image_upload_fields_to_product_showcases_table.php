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
        Schema::table('product_showcases', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('image_url');
            $table->string('image_filename')->nullable()->after('image_path');
            $table->string('image_original_name')->nullable()->after('image_filename');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_showcases', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'image_filename', 'image_original_name']);
        });
    }
};
