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
        Schema::create('tb_info_pengiriman_produk', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_produk');
            $table->unsignedBigInteger('id_kota_asal');
            $table->string('nama_kota_asal');
            $table->json('estimasi_pengiriman')->nullable(); // {jne: "2-3 hari", pos: "3-5 hari"}
            $table->decimal('berat_pengiriman', 8, 2)->default(0);
            $table->json('dimensi_pengiriman')->nullable();
            $table->decimal('biaya_pengemasan', 8, 2)->default(0);
            $table->boolean('is_gratis_ongkir')->default(false);
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('id_produk')->references('id')->on('tb_produk')->onDelete('cascade');
            $table->foreign('id_kota_asal')->references('id')->on('tb_master_kota')->onDelete('cascade');
            $table->index('id_produk');
            $table->index('id_kota_asal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_info_pengiriman_produk');
    }
};