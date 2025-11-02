<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('is_editor_pick')->default(false)->after('trending_order');
            $table->integer('editor_pick_order')->default(0)->after('is_editor_pick');
            $table->index('is_editor_pick');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['is_editor_pick', 'editor_pick_order']);
        });
    }
};
