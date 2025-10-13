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
        Schema::create('tb_gambar_produk', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_produk');
            $table->unsignedBigInteger('id_harga_varian')->nullable();
            $table->string('url_gambar');
            $table->string('alt_text')->nullable();
            $table->integer('urutan_gambar')->default(0);
            $table->boolean('is_gambar_utama')->default(false);
            $table->enum('tipe_gambar', ['GALERI', 'DESKRIPSI', 'VARIAN'])->default('GALERI');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('id_produk')->references('id')->on('tb_produk')->onDelete('cascade');
            $table->foreign('id_harga_varian')->references('id')->on('tb_harga_varian_produk')->onDelete('set null');
            $table->index('id_produk');
            $table->index('id_harga_varian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_gambar_produk');
    }
};