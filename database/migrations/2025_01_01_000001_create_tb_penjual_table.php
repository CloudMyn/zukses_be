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
        Schema::create('tb_penjual', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->string('nama_toko')->unique();
            $table->string('slug_toko')->unique();
            $table->text('deskripsi_toko')->nullable();
            $table->string('logo_toko')->nullable();
            $table->string('banner_toko')->nullable();
            $table->string('nomor_ktp')->unique();
            $table->string('foto_ktp')->nullable();
            $table->string('nomor_npwp')->nullable()->unique();
            $table->string('foto_npwp')->nullable();
            $table->enum('jenis_usaha', ['INDIVIDU', 'PERUSAHAAN'])->default('INDIVIDU');
            $table->enum('status_verifikasi', ['MENUNGGU', 'TERVERIFIKASI', 'DITOLAK', 'PERLU_DIREVISI'])->default('MENUNGGU');
            $table->date('tanggal_verifikasi')->nullable();
            $table->unsignedBigInteger('id_verifikator')->nullable();
            $table->text('catatan_verifikasi')->nullable();
            $table->decimal('rating_toko', 3, 2)->default(0);
            $table->integer('total_penjualan')->default(0);
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_verifikator')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_penjual');
    }
};
