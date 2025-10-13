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
        Schema::create('tb_chat_peserta_percakapan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_obrolan'); // Foreign key to tb_chat_percakapan table
            $table->unsignedBigInteger('id_user')->nullable(); // Foreign key to users table
            $table->unsignedBigInteger('id_penjual')->nullable(); // Foreign key to tb_penjual table
            $table->enum('status_partisipan', ['aktif', 'keluar', 'diblokir'])->default('aktif');
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_muted')->default(false);
            $table->timestamp('tanggal_join')->useCurrent();
            $table->timestamp('tanggal_keluar')->nullable();
            $table->text('catatan_partisipan')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints
            $table->foreign('id_obrolan')->references('id')->on('tb_chat_percakapan')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_penjual')->references('id')->on('tb_penjual')->onDelete('cascade');
            
            // Ensure a user or seller can only be once in a conversation
            $table->unique(['id_obrolan', 'id_user']);
            $table->unique(['id_obrolan', 'id_penjual']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_chat_peserta_percakapan');
    }
};
