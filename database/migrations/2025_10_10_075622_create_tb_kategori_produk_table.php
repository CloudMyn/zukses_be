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
        Schema::create('tb_kategori_produk', function (Blueprint $table) {
            $table->id('id');
            $table->string('nama_kategori');
            $table->string('slug_kategori')->unique();
            $table->text('deskripsi_kategori')->nullable();
            $table->string('gambar_kategori')->nullable();
            $table->string('icon_kategori')->nullable();
            $table->unsignedBigInteger('id_kategori_induk')->nullable();
            $table->integer('level_kategori')->default(0);
            $table->integer('urutan_tampilan')->default(0);
            $table->boolean('is_kategori_aktif')->default(true);
            $table->boolean('is_kategori_featured')->default(false);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('id_kategori_induk')->references('id')->on('tb_kategori_produk')->onDelete('set null');
            $table->index(['level_kategori', 'urutan_tampilan']);
            $table->index('is_kategori_aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_kategori_produk');
    }
};
