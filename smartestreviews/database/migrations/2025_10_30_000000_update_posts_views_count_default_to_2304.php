<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all existing posts that have views_count less than 2304
        // This ensures all existing posts start with at least 2304 views
        DB::table('posts')
            ->where(function ($query) {
                $query->where('views_count', '<', 2304)
                      ->orWhereNull('views_count');
            })
            ->update(['views_count' => 2304]);
        
        // For databases that support ALTER COLUMN (MySQL, PostgreSQL), update default
        // SQLite doesn't support changing default values, so we rely on model boot method
        $driver = DB::connection()->getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE posts MODIFY COLUMN views_count INT DEFAULT 2304");
        } elseif ($driver === 'pgsql') {
            DB::statement("ALTER TABLE posts ALTER COLUMN views_count SET DEFAULT 2304");
        }
        // For SQLite, the model boot method will handle setting 2304 as default
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to default 0 for databases that support it
        $driver = DB::connection()->getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE posts MODIFY COLUMN views_count INT DEFAULT 0");
        } elseif ($driver === 'pgsql') {
            DB::statement("ALTER TABLE posts ALTER COLUMN views_count SET DEFAULT 0");
        }
        // For SQLite, no change needed as default wasn't changed in DB
    }
};

