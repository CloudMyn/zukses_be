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
        Schema::create('tb_log_inventori', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_produk');
            $table->unsignedBigInteger('id_harga_varian')->nullable();
            $table->enum('tipe_transaksi', ['MASUK', 'KELUAR', 'PENYESUAIAN', 'RUSAK', 'KEMBALI']);
            $table->integer('jumlah_transaksi');
            $table->integer('stok_sebelum');
            $table->integer('stok_sesudah');
            $table->string('alasan_transaksi')->nullable();
            $table->unsignedBigInteger('id_operator');
            $table->text('catatan_tambahan')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();

            $table->foreign('id_produk')->references('id')->on('tb_produk')->onDelete('cascade');
            $table->foreign('id_harga_varian')->references('id')->on('tb_harga_varian_produk')->onDelete('set null');
            $table->foreign('id_operator')->references('id')->on('users')->onDelete('cascade');
            $table->index('id_produk');
            $table->index('id_harga_varian');
            $table->index('id_operator');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_log_inventori');
    }
};