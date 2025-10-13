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
        Schema::create('tb_metode_pengiriman', function (Blueprint $table) {
            $table->id();
            $table->string('nama_metode', 100);
            $table->string('kode_metode', 50)->unique();
            $table->text('deskripsi_metode')->nullable();
            $table->string('icon_metode', 255)->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->integer('urutan_tampilan')->default(0);
            $table->json('konfigurasi_pengiriman')->nullable(); // For storing shipping settings
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_metode_pengiriman');
    }
};
