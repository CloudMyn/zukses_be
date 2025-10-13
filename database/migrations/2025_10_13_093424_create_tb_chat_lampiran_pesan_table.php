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
        Schema::create('tb_chat_lampiran_pesan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pesan'); // Foreign key to tb_chat_pesan_chat table
            $table->string('nama_file', 255);
            $table->string('path_file', 500);
            $table->string('url_file', 500);
            $table->enum('jenis_file', ['gambar', 'video', 'audio', 'dokumen', 'lainnya'])->default('gambar');
            $table->integer('ukuran_file'); // In bytes
            $table->string('mime_type', 100);
            $table->text('deskripsi_file')->nullable();
            $table->boolean('is_thumbnail')->default(false);
            $table->integer('urutan_tampilan')->default(0);
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints
            $table->foreign('id_pesan')->references('id')->on('tb_chat_pesan_chat')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_chat_lampiran_pesan');
    }
};
