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
        Schema::create('tb_pengaturan_sistem', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pengaturan', 100)->unique();
            $table->string('label_pengaturan', 255)->nullable();
            $table->text('deskripsi_pengaturan')->nullable();
            $table->string('jenis_pengaturan', 50); // 'text', 'number', 'boolean', 'json', 'select', etc.
            $table->text('nilai_pengaturan');
            $table->json('opsi_pengaturan')->nullable(); // For storing options for select inputs
            $table->string('kategori_pengaturan', 100)->nullable();
            $table->integer('urutan_pengaturan')->default(0);
            $table->boolean('is_aktif')->default(true);
            $table->unsignedBigInteger('id_user_pembaharui')->nullable(); // Foreign key to users table
            $table->timestamp('tanggal_pembaharuan')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints
            $table->foreign('id_user_pembaharui')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_pengaturan_sistem');
    }
};
