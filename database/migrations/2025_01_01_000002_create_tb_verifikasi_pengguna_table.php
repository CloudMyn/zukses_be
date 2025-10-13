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
        Schema::create('tb_verifikasi_pengguna', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user')->nullable();
            $table->enum('jenis_verifikasi', ['EMAIL', 'TELEPON', 'KTP', 'NPWP']);
            $table->string('nilai_verifikasi'); // email, nomor telepon, dll
            $table->string('kode_verifikasi');
            $table->timestamp('kedaluwarsa_pada');
            $table->boolean('telah_digunakan')->default(false);
            $table->integer('jumlah_coba')->default(0);
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_verifikasi_pengguna');
    }
};
