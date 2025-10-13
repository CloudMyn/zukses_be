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
        Schema::create('tb_ulasan_produk', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_produk'); // Foreign key to tb_produk table
            $table->unsignedBigInteger('id_harga_varian')->nullable(); // Foreign key to tb_harga_varian_produk table
            $table->unsignedBigInteger('id_user'); // Foreign key to users table
            $table->unsignedBigInteger('id_pesanan')->nullable(); // Foreign key to tb_pesanan table
            $table->integer('rating')->unsigned()->default(5)->comment('Rating from 1 to 5');
            $table->text('komentar_ulasan')->nullable();
            $table->boolean('is_verified_purchase')->default(false);
            $table->boolean('is_aktif')->default(true);
            $table->integer('jumlah_suara_positif')->default(0);
            $table->integer('jumlah_suara_negatif')->default(0);
            $table->timestamp('tanggal_ulasan')->useCurrent();
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->timestamp('tanggal_dibatalkan')->nullable();
            $table->text('keterangan_pembatalan')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints
            $table->foreign('id_produk')->references('id')->on('tb_produk')->onDelete('cascade');
            $table->foreign('id_harga_varian')->references('id')->on('tb_harga_varian_produk')->onDelete('set null');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_pesanan')->references('id')->on('tb_pesanan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_ulasan_produk');
    }
};
