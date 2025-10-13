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
        Schema::create('tb_notifikasi_pengguna', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user'); // Foreign key to users table
            $table->string('judul_notifikasi', 255);
            $table->text('isi_notifikasi');
            $table->string('jenis_notifikasi', 50); // 'system', 'order', 'payment', 'product', etc.
            $table->string('kategori_notifikasi', 50)->nullable();
            $table->json('metadata_notifikasi')->nullable(); // For storing additional data
            $table->boolean('is_dibaca')->default(false);
            $table->timestamp('tanggal_dibaca')->nullable();
            $table->timestamp('tanggal_notifikasi')->useCurrent();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_notifikasi_pengguna');
    }
};
