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
        Schema::create('tb_tarif_pengiriman', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_metode_pengiriman'); // Foreign key to tb_metode_pengiriman table
            $table->unsignedBigInteger('id_provinsi_asal')->nullable(); // Foreign key to master_provinsi table
            $table->unsignedBigInteger('id_kota_asal')->nullable(); // Foreign key to master_kota table
            $table->unsignedBigInteger('id_provinsi_tujuan'); // Foreign key to master_provinsi table
            $table->unsignedBigInteger('id_kota_tujuan'); // Foreign key to master_kota table
            $table->decimal('berat_minimal', 5, 2)->default(0.00);
            $table->decimal('berat_maksimal', 8, 2)->nullable(); // If null, means unlimited
            $table->decimal('ongkir', 10, 2);
            $table->string('keterangan_tarif', 255)->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            $table->index('id_metode_pengiriman');
            $table->index('id_provinsi_asal');
            $table->index('id_kota_asal');
            $table->index('id_provinsi_tujuan');
            $table->index('id_kota_tujuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_tarif_pengiriman');
    }
};
