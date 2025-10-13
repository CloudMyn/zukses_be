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
        Schema::create('tb_pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pesanan', 50)->unique();
            $table->unsignedBigInteger('id_customer')->nullable(); // Foreign key to users table
            $table->unsignedBigInteger('id_alamat_pengiriman')->nullable(); // Foreign key to tb_alamat table
            $table->enum('status_pesanan', ['baru', 'dikonfirmasi', 'diproses', 'dikirim', 'selesai', 'dibatalkan'])->default('baru');
            $table->enum('status_pembayaran', ['belum_dibayar', 'dibayar', 'gagal', 'dibatalkan'])->default('belum_dibayar');
            $table->integer('total_items')->default(0);
            $table->decimal('total_berat', 10, 2)->default(0.00);
            $table->decimal('subtotal_produk', 15, 2)->default(0.00);
            $table->decimal('total_diskon_produk', 10, 2)->default(0.00);
            $table->decimal('total_ongkir', 10, 2)->default(0.00);
            $table->decimal('total_biaya_layanan', 10, 2)->default(0.00);
            $table->decimal('total_pajak', 10, 2)->default(0.00);
            $table->decimal('total_pembayaran', 15, 2)->default(0.00);
            $table->string('metode_pembayaran', 50)->nullable();
            $table->string('bank_pembayaran', 50)->nullable();
            $table->string('va_number', 50)->nullable();
            $table->timestamp('deadline_pembayaran')->nullable();
            $table->timestamp('tanggal_dibayar')->nullable();
            $table->string('no_resi', 100)->nullable();
            $table->text('catatan_pesanan')->nullable();
            $table->timestamp('tanggal_pengiriman')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamp('tanggal_dibatalkan')->nullable();
            $table->text('alasan_pembatalan')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints
            $table->foreign('id_customer')->references('id')->on('users')->onDelete('set null');
            $table->foreign('id_alamat_pengiriman')->references('id')->on('tb_alamat')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_pesanan');
    }
};
