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
        Schema::create('tb_chat_pesan_chat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_obrolan'); // Foreign key to tb_chat_percakapan table
            $table->unsignedBigInteger('id_pengirim'); // Foreign key to users or tb_penjual
            $table->enum('jenis_pengirim', ['pembeli', 'penjual'])->default('pembeli');
            $table->text('isi_pesan')->nullable();
            $table->enum('jenis_pesan', ['teks', 'gambar', 'video', 'dokumen', 'lokasi', 'produk', 'order'])->default('teks');
            $table->unsignedBigInteger('id_pesan_induk')->nullable(); // For replies, foreign key to same table
            $table->timestamp('tanggal_pesan')->useCurrent();
            $table->timestamp('dibaca_pada')->nullable();
            $table->timestamp('ditarik_pada')->nullable(); // When message is deleted/withdrawn
            $table->integer('jumlah_baca')->default(0);
            $table->integer('jumlah_diteruskan')->default(0);
            $table->json('metadata')->nullable(); // For storing additional message data
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints
            $table->foreign('id_obrolan')->references('id')->on('tb_chat_percakapan')->onDelete('cascade');
            $table->foreign('id_pesan_induk')->references('id')->on('tb_chat_pesan_chat')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_chat_pesan_chat');
    }
};
