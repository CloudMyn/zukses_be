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
        Schema::create('tb_chat_reaksi_pesan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pesan'); // Foreign key to tb_chat_pesan_chat table
            $table->unsignedBigInteger('id_user'); // Foreign key to users table
            $table->string('jenis_reaksi', 20); // Like, love, laugh, wow, sad, angry, etc.
            $table->timestamp('tanggal_reaksi')->useCurrent();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints
            $table->foreign('id_pesan')->references('id')->on('tb_chat_pesan_chat')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            
            // Ensure a user can only react once per message
            $table->unique(['id_pesan', 'id_user']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_chat_reaksi_pesan');
    }
};
