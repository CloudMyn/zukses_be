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
        Schema::create('tb_alamat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->string('label_alamat'); // "Rumah", "Kantor", "Gudang", dll
            $table->string('nama_penerima');
            $table->string('nomor_telepon_penerima');
            $table->text('alamat_lengkap');
            $table->unsignedBigInteger('id_provinsi');
            $table->string('nama_provinsi');
            $table->unsignedBigInteger('id_kabupaten');
            $table->string('nama_kabupaten');
            $table->unsignedBigInteger('id_kecamatan');
            $table->string('nama_kecamatan');
            $table->unsignedBigInteger('id_kelurahan');
            $table->string('nama_kelurahan');
            $table->string('kode_pos');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('adalah_alamat_utama')->default(false);
            $table->enum('tipe_alamat', ['RUMAH', 'KANTOR', 'GUDANG', 'LAINNYA'])->default('RUMAH');
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_alamat');
    }
};
