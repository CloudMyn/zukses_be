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
        // Add foreign key constraints to tb_riwayat_penggunaan_promosi table if they don't exist
        try {
            Schema::table('tb_riwayat_penggunaan_promosi', function (Blueprint $table) {
                $table->foreign('id_promosi')->references('id')->on('tb_promosi');
            });
        } catch (\Exception $e) {
            // Foreign key already exists, continue
        }

        try {
            Schema::table('tb_riwayat_penggunaan_promosi', function (Blueprint $table) {
                $table->foreign('id_pengguna')->references('id')->on('users');
            });
        } catch (\Exception $e) {
            // Foreign key already exists, continue
        }

        try {
            Schema::table('tb_riwayat_penggunaan_promosi', function (Blueprint $table) {
                $table->foreign('id_pesanan')->references('id')->on('tb_pesanan');
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
        // Drop foreign key constraints from tb_riwayat_penggunaan_promosi table if they exist
        try {
            Schema::table('tb_riwayat_penggunaan_promosi', function (Blueprint $table) {
                $table->dropForeign(['id_promosi']);
            });
        } catch (\Exception $e) {
            // Foreign key doesn't exist, continue
        }

        try {
            Schema::table('tb_riwayat_penggunaan_promosi', function (Blueprint $table) {
                $table->dropForeign(['id_pengguna']);
            });
        } catch (\Exception $e) {
            // Foreign key doesn't exist, continue
        }

        try {
            Schema::table('tb_riwayat_penggunaan_promosi', function (Blueprint $table) {
                $table->dropForeign(['id_pesanan']);
            });
        } catch (\Exception $e) {
            // Foreign key doesn't exist, continue
        }
    }
};