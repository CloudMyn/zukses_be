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
        Schema::create('tb_master_kecamatan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kota_id');
            $table->string('nama');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('kota_id')->references('id')->on('tb_master_kota')->onDelete('cascade');
            $table->index('kota_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_master_kecamatan');
    }
};