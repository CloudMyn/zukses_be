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
        // Add foreign key constraints to tb_promosi table if they don't exist
        try {
            Schema::table('tb_promosi', function (Blueprint $table) {
                $table->foreign('id_kategori_produk')->references('id')->on('tb_kategori_produk')->onDelete('set null');
            });
        } catch (\Exception $e) {
            // Foreign key already exists, continue
        }

        try {
            Schema::table('tb_promosi', function (Blueprint $table) {
                $table->foreign('id_pembuat')->references('id')->on('users')->onDelete('cascade');
            });
        } catch (\Exception $e) {
            // Foreign key already exists, continue
        }

        try {
            Schema::table('tb_promosi', function (Blueprint $table) {
                $table->foreign('id_pembaharu_terakhir')->references('id')->on('users')->onDelete('set null');
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
        // Drop foreign key constraints from tb_promosi table if they exist
        try {
            Schema::table('tb_promosi', function (Blueprint $table) {
                $table->dropForeign(['id_kategori_produk']);
            });
        } catch (\Exception $e) {
            // Foreign key doesn't exist, continue
        }

        try {
            Schema::table('tb_promosi', function (Blueprint $table) {
                $table->dropForeign(['id_pembuat']);
            });
        } catch (\Exception $e) {
            // Foreign key doesn't exist, continue
        }

        try {
            Schema::table('tb_promosi', function (Blueprint $table) {
                $table->dropForeign(['id_pembaharu_terakhir']);
            });
        } catch (\Exception $e) {
            // Foreign key doesn't exist, continue
        }
    }
};