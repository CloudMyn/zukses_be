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
        Schema::create('tb_pengiriman_pesanan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pesanan'); // Foreign key to tb_pesanan table
            $table->unsignedBigInteger('id_metode_pengiriman')->nullable(); // Foreign key to tb_metode_pengiriman table
            $table->unsignedBigInteger('id_alamat_pengiriman')->nullable(); // Foreign key to tb_alamat table
            $table->string('no_resi', 100)->nullable();
            $table->enum('status_pengiriman', ['diproses', 'dikemas', 'dikirim', 'diterima', 'dibatalkan', 'hilang'])->default('diproses');
            $table->decimal('berat_total', 10, 2);
            $table->decimal('ongkir', 10, 2);
            $table->text('keterangan_pengiriman')->nullable();
            $table->timestamp('tanggal_pengiriman')->nullable();
            $table->timestamp('tanggal_diterima')->nullable();
            $table->string('nama_penerima', 100)->nullable();
            $table->string('nomor_telepon_penerima', 20)->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints - note: some foreign keys are added separately to handle dependency issues
            $table->index('id_pesanan');
            $table->index('id_metode_pengiriman');
            $table->index('id_alamat_pengiriman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_pengiriman_pesanan');
    }
};
