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
        // Add foreign key constraints to tb_produk_promosi table if they don't exist
        try {
            Schema::table('tb_produk_promosi', function (Blueprint $table) {
                $table->foreign('id_promosi')->references('id')->on('tb_promosi')->onDelete('cascade');
            });
        } catch (\Exception $e) {
            // Foreign key already exists, continue
        }

        try {
            Schema::table('tb_produk_promosi', function (Blueprint $table) {
                $table->foreign('id_produk')->references('id')->on('tb_produk')->onDelete('cascade');
            });
        } catch (\Exception $e) {
            // Foreign key already exists, continue
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key constraints from tb_produk_promosi table if they exist
        try {
            Schema::table('tb_produk_promosi', function (Blueprint $table) {
                $table->dropForeign(['id_promosi']);
            });
        } catch (\Exception $e) {
            // Foreign key doesn't exist, continue
        }

        try {
            Schema::table('tb_produk_promosi', function (Blueprint $table) {
                $table->dropForeign(['id_produk']);
            });
        } catch (\Exception $e) {
            // Foreign key doesn't exist, continue
        }
    }
};