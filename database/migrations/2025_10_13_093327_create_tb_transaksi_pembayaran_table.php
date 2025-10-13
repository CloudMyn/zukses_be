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
        Schema::create('tb_transaksi_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pesanan'); // Foreign key to tb_pesanan table
            $table->unsignedBigInteger('id_metode_pembayaran')->nullable(); // Foreign key to tb_metode_pembayaran table
            $table->string('kode_transaksi', 100)->unique();
            $table->enum('status_pembayaran', ['menunggu', 'dibayar', 'gagal', 'dibatalkan', 'dikembalikan'])->default('menunggu');
            $table->decimal('jumlah_pembayaran', 15, 2);
            $table->string('kode_pembayaran', 100)->nullable();
            $table->string('va_number', 50)->nullable();
            $table->string('payment_url', 500)->nullable();
            $table->text('keterangan_pembayaran')->nullable();
            $table->timestamp('tanggal_pembayaran')->nullable();
            $table->timestamp('tanggal_jatuh_tempo')->nullable();
            $table->timestamp('tanggal_dibatalkan')->nullable();
            $table->timestamp('tanggal_dikembalikan')->nullable();
            $table->text('keterangan_dibatalkan')->nullable();
            $table->json('metadata_pembayaran')->nullable(); // For storing gateway-specific data
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints
            $table->foreign('id_pesanan')->references('id')->on('tb_pesanan')->onDelete('cascade');
            $table->foreign('id_metode_pembayaran')->references('id')->on('tb_metode_pembayaran')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_transaksi_pembayaran');
    }
};
