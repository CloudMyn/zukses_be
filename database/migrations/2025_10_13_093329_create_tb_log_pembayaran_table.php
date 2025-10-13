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
        Schema::create('tb_log_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_transaksi_pembayaran'); // Foreign key to tb_transaksi_pembayaran table
            $table->enum('status_lama', ['menunggu', 'dibayar', 'gagal', 'dibatalkan', 'dikembalikan'])->nullable();
            $table->enum('status_baru', ['menunggu', 'dibayar', 'gagal', 'dibatalkan', 'dikembalikan']);
            $table->text('keterangan_log')->nullable();
            $table->json('data_transaksi_lama')->nullable(); // Store previous state
            $table->json('data_transaksi_baru')->nullable(); // Store new state
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('tanggal_log')->useCurrent();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints
            $table->foreign('id_transaksi_pembayaran')->references('id')->on('tb_transaksi_pembayaran')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_log_pembayaran');
    }
};
