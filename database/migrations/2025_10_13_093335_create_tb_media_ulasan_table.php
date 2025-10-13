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
        Schema::create('tb_media_ulasan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_ulasan_produk'); // Foreign key to tb_ulasan_produk table
            $table->string('jenis_media', 20); // 'image', 'video', etc.
            $table->string('nama_file', 255);
            $table->string('path_file', 500);
            $table->string('url_media', 500);
            $table->string('caption_media', 500)->nullable();
            $table->integer('urutan_tampilan')->default(0);
            $table->boolean('is_aktif')->default(true);
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints
            $table->foreign('id_ulasan_produk')->references('id')->on('tb_ulasan_produk')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_media_ulasan');
    }
};
