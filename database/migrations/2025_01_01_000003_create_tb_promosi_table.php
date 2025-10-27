<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tb_promosi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_promosi', 50)->unique();
            $table->string('nama_promosi', 255);
            $table->text('deskripsi')->nullable();
            $table->string('jenis_promosi');
            $table->string('tipe_diskon');
            $table->decimal('nilai_diskon', 20, 2);
            $table->integer('jumlah_maksimum_penggunaan')->default(0);
            $table->integer('jumlah_penggunaan_saat_ini')->default(0);
            $table->integer('jumlah_maksimum_penggunaan_per_pengguna')->default(0);
            $table->timestamp('tanggal_mulai');
            $table->timestamp('tanggal_berakhir');
            $table->decimal('minimum_pembelian', 10, 2)->default(0);
            $table->unsignedBigInteger('id_kategori_produk')->nullable();
            $table->boolean('dapat_digabungkan')->default(false);
            $table->boolean('status_aktif')->default(false);
            $table->unsignedBigInteger('id_pembuat');
            $table->unsignedBigInteger('id_pembaharu_terakhir')->nullable();
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
            
            // Foreign Keys will be added in a separate migration after all tables exist
            // $table->foreign('id_kategori_produk')->references('id')->on('tb_kategori_produk');
            // $table->foreign('id_pembuat')->references('id')->on('users');
            // $table->foreign('id_pembaharu_terakhir')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tb_promosi');
    }
};