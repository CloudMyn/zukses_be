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
        Schema::create('tb_chat_edit_pesan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pesan'); // Foreign key to tb_chat_pesan_chat table
            $table->unsignedBigInteger('id_user_editor')->nullable(); // Foreign key to users table
            $table->text('isi_pesan_lama');
            $table->text('isi_pesan_baru');
            $table->timestamp('tanggal_edit')->useCurrent();
            $table->text('alasan_edit')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints
            $table->foreign('id_pesan')->references('id')->on('tb_chat_pesan_chat')->onDelete('cascade');
            $table->foreign('id_user_editor')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_chat_edit_pesan');
    }
};
