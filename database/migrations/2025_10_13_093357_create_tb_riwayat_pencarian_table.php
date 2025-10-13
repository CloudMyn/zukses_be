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
        Schema::create('tb_riwayat_pencarian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user')->nullable(); // Foreign key to users table
            $table->string('kata_kunci_pencarian', 255);
            $table->integer('jumlah_hasil')->default(0);
            $table->string('jenis_pencarian', 50)->default('produk'); // 'produk', 'penjual', 'kategori', etc.
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('parameter_pencarian')->nullable(); // For storing additional search parameters
            $table->timestamp('tanggal_pencarian')->useCurrent();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints
            $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_riwayat_pencarian');
    }
};
