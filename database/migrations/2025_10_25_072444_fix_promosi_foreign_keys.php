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
        // Add foreign key constraints to existing promosi table
        // We'll try adding them and ignore if they already exist
        try {
            Schema::table('tb_promosi', function (Blueprint $table) {
                $table->foreign('id_kategori_produk')->references('id')->on('tb_kategori_produk');
            });
        } catch (\Exception $e) {
            // Foreign key already exists, continue
        }
        
        try {
            Schema::table('tb_promosi', function (Blueprint $table) {
                $table->foreign('id_pembuat')->references('id')->on('users');
            });
        } catch (\Exception $e) {
            // Foreign key already exists, continue
        }
        
        try {
            Schema::table('tb_promosi', function (Blueprint $table) {
                $table->foreign('id_pembaharu_terakhir')->references('id')->on('users');
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
        Schema::table('tb_promosi', function (Blueprint $table) {
            $table->dropForeign(['id_kategori_produk']);
            $table->dropForeign(['id_pembuat']);
            $table->dropForeign(['id_pembaharu_terakhir']);
        });
    }
};
