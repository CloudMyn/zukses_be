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
        Schema::create('tb_suara_ulasan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_ulasan_produk'); // Foreign key to tb_ulasan_produk table
            $table->unsignedBigInteger('id_user'); // Foreign key to users table
            $table->enum('jenis_suara', ['positif', 'negatif']);
            $table->timestamp('tanggal_suara')->useCurrent();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints
            $table->foreign('id_ulasan_produk')->references('id')->on('tb_ulasan_produk')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            
            // Ensure a user can only vote once per review
            $table->unique(['id_ulasan_produk', 'id_user']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_suara_ulasan');
    }
};
