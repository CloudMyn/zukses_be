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
        Schema::create('tb_chat_percakapan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_obrolan', 255)->nullable();
            $table->text('deskripsi_obrolan')->nullable();
            $table->enum('jenis_obrolan', ['perorangan', 'grup', 'penjual_pembeli'])->default('perorangan');
            $table->boolean('is_group')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('jumlah_partisipan')->default(0);
            $table->unsignedBigInteger('id_pembuat')->nullable(); // Foreign key to users table or tb_penjual
            $table->timestamp('tanggal_pembuatan')->useCurrent();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints
            $table->foreign('id_pembuat')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_chat_percakapan');
    }
};
