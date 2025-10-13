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
        Schema::create('tb_chat_referensi_order_chat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_obrolan'); // Foreign key to tb_chat_percakapan table
            $table->unsignedBigInteger('id_order'); // Foreign key to tb_pesanan table
            $table->unsignedBigInteger('id_pesan'); // Foreign key to tb_chat_pesan_chat table
            $table->text('keterangan_referensi')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints
            $table->foreign('id_obrolan')->references('id')->on('tb_chat_percakapan')->onDelete('cascade');
            $table->foreign('id_order')->references('id')->on('tb_pesanan')->onDelete('cascade');
            $table->foreign('id_pesan')->references('id')->on('tb_chat_pesan_chat')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_chat_referensi_order_chat');
    }
};
