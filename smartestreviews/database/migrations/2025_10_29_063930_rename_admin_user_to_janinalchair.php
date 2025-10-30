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
        // Rename the first admin-like user to "Janinalchair"
        DB::table('users')
            ->where(function ($q) {
                $q->where('id', 1)
                  ->orWhere('email', 'admin@admin.com')
                  ->orWhere('name', 'like', '%admin%');
            })
            ->update(['name' => 'Janinalchair']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Best-effort rollback: if current name is Janinalchair and email typical admin,
        // set back to "Admin User"
        DB::table('users')
            ->where('name', 'Janinalchair')
            ->where(function ($q) {
                $q->where('id', 1)->orWhere('email', 'admin@admin.com');
            })
            ->update(['name' => 'Admin User']);
    }
};
