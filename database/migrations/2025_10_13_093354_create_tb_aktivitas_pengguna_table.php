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
        Schema::create('tb_aktivitas_pengguna', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user'); // Foreign key to users table
            $table->unsignedBigInteger('id_sesi_pengguna')->nullable(); // Foreign key to tb_sesi_pengguna table
            $table->string('jenis_aktivitas', 100); // 'login', 'logout', 'browse', 'search', 'order', etc.
            $table->string('deskripsi_aktivitas', 255)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata_aktivitas')->nullable(); // For storing additional data
            $table->timestamp('tanggal_aktivitas')->useCurrent();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            $table->index('id_user');
            $table->index('id_sesi_pengguna');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_aktivitas_pengguna');
    }
};
