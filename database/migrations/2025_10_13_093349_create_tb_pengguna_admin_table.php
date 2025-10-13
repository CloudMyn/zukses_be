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
        Schema::create('tb_pengguna_admin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user'); // Foreign key to users table
            $table->string('nip', 50)->unique();
            $table->string('nama_lengkap', 255);
            $table->string('nomor_telepon', 20)->nullable();
            $table->string('departemen', 100)->nullable();
            $table->string('jabatan', 100)->nullable();
            $table->date('tanggal_mulai_bekerja')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('catatan')->nullable();
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
        Schema::dropIfExists('tb_pengguna_admin');
    }
};
