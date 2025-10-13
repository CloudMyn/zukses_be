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
        Schema::create('tb_item_keranjang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cart'); // Foreign key to tb_keranjang_belanja table
            $table->unsignedBigInteger('id_produk'); // Foreign key to tb_produk table
            $table->unsignedBigInteger('id_harga_varian'); // Foreign key to tb_harga_varian_produk table
            $table->integer('kuantitas')->default(1);
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('harga_total', 15, 2);
            $table->decimal('diskon_item', 10, 2)->default(0.00);
            $table->text('catatan_item')->nullable();
            $table->string('gambar_produk', 255)->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints
            $table->foreign('id_cart')->references('id')->on('tb_keranjang_belanja')->onDelete('cascade');
            $table->foreign('id_produk')->references('id')->on('tb_produk')->onDelete('cascade');
            $table->foreign('id_harga_varian')->references('id')->on('tb_harga_varian_produk')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_item_keranjang');
    }
};
