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
        Schema::create('tb_pengiriman_varian_produk', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('harga_varian_id');
            $table->float('berat', 8, 2)->default(0);
            $table->float('panjang', 8, 2)->default(0);
            $table->float('lebar', 8, 2)->default(0);
            $table->float('tinggi', 8, 2)->default(0);
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('harga_varian_id')->references('id')->on('tb_harga_varian_produk')->onDelete('cascade');
            $table->index('harga_varian_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_pengiriman_varian_produk');
    }
};