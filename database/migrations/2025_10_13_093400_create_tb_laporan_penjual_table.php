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
        Schema::create('tb_laporan_penjual', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_penjual'); // Foreign key to tb_penjual table
            $table->string('judul_laporan', 255);
            $table->text('deskripsi_laporan')->nullable();
            $table->enum('jenis_laporan', ['mingguan', 'bulanan', 'tahunan', 'khusus'])->default('bulanan');
            $table->date('tanggal_mulai_laporan');
            $table->date('tanggal_selesai_laporan');
            $table->json('data_laporan'); // For storing the report data
            $table->decimal('total_pendapatan', 15, 2)->default(0.00);
            $table->integer('jumlah_pesanan')->default(0);
            $table->integer('jumlah_produk_terjual')->default(0);
            $table->decimal('rata_rata_rating', 3, 2)->default(0.00);
            $table->string('file_laporan', 500)->nullable(); // Path to generated report file
            $table->boolean('is_digenerate_oleh_sistem')->default(false);
            $table->timestamp('tanggal_digenerate')->useCurrent();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints
            $table->foreign('id_penjual')->references('id')->on('tb_penjual')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_laporan_penjual');
    }
};
