<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for testing environment.
     */
    public function up(): void
    {
        // Only apply for SQLite (testing environment)
        if (config('database.default') === 'sqlite') {
            // Drop fullText index if it exists and recreate as regular index
            Schema::table('tb_produk', function (Blueprint $table) {
                // First, check if the column exists and add regular index instead
                if (Schema::hasColumn('tb_produk', 'deskripsi_lengkap')) {
                    $table->index('deskripsi_lengkap');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nothing to reverse for testing environment
    }
};