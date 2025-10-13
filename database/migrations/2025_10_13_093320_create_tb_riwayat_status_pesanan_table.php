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
        Schema::create('tb_riwayat_status_pesanan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pesanan'); // Foreign key to tb_pesanan table
            $table->enum('status_lama', ['baru', 'dikonfirmasi', 'diproses', 'dikirim', 'selesai', 'dibatalkan'])->nullable();
            $table->enum('status_baru', ['baru', 'dikonfirmasi', 'diproses', 'dikirim', 'selesai', 'dibatalkan']);
            $table->text('keterangan_perubahan')->nullable();
            $table->unsignedBigInteger('id_user_perubahan')->nullable(); // Foreign key to users table
            $table->timestamp('tanggal_perubahan')->useCurrent();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints
            $table->foreign('id_pesanan')->references('id')->on('tb_pesanan')->onDelete('cascade');
            $table->foreign('id_user_perubahan')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_riwayat_status_pesanan');
    }
};
