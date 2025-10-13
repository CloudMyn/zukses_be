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
        Schema::create('tb_chat_laporan_percakapan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_obrolan'); // Foreign key to tb_chat_percakapan table
            $table->unsignedBigInteger('id_pelapor'); // Foreign key to users table
            $table->unsignedBigInteger('id_pelanggar'); // Foreign key to users table
            $table->enum('jenis_pelanggaran', ['spam', 'konten_membingungkan', 'ujaran_kebencian', 'ancaman', 'penipuan', 'lainnya'])->default('lainnya');
            $table->text('deskripsi_pelanggaran');
            $table->text('bukti_pelanggaran')->nullable();
            $table->enum('status_laporan', ['baru', 'diproses', 'ditinjau', 'ditolak', 'diterima'])->default('baru');
            $table->timestamp('tanggal_laporan')->useCurrent();
            $table->timestamp('tanggal_review')->nullable();
            $table->text('catatan_review')->nullable();
            $table->unsignedBigInteger('id_admin_reviewer')->nullable(); // Foreign key to users table
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints
            $table->foreign('id_obrolan')->references('id')->on('tb_chat_percakapan')->onDelete('cascade');
            $table->foreign('id_pelapor')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_pelanggar')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_admin_reviewer')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_chat_laporan_percakapan');
    }
};
