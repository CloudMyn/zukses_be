<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tb_riwayat_penggunaan_promosi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_promosi');
            $table->unsignedBigInteger('id_pengguna');
            $table->unsignedBigInteger('id_pesanan')->nullable();
            $table->timestamp('tanggal_penggunaan');
            $table->decimal('jumlah_diskon_diterapkan', 10, 2);
            $table->timestamp('dibuat_pada')->nullable();
            
            // Foreign Keys will be added in a separate migration after all tables exist
            // $table->foreign('id_promosi')->references('id')->on('tb_promosi');
            // $table->foreign('id_pengguna')->references('id')->on('users');
            // $table->foreign('id_pesanan')->references('id')->on('tb_pesanan');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tb_riwayat_penggunaan_promosi');
    }
};