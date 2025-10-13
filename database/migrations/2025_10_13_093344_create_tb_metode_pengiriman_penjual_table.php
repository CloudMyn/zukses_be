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
        Schema::create('tb_metode_pengiriman_penjual', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_penjual'); // Foreign key to tb_penjual table
            $table->unsignedBigInteger('id_metode_pengiriman'); // Foreign key to tb_metode_pengiriman table
            $table->boolean('is_aktif')->default(true);
            $table->text('keterangan_metode_penjual')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints - id_metode_pengiriman constraint added separately due to migration order
            $table->foreign('id_penjual')->references('id')->on('tb_penjual')->onDelete('cascade');
            $table->index('id_metode_pengiriman');
            
            // Ensure a seller can only have one active method per shipping type
            $table->unique(['id_penjual', 'id_metode_pengiriman'], 'unique_penjual_pengiriman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_metode_pengiriman_penjual');
    }
};
