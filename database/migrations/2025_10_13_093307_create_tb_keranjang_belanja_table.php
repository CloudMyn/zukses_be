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
        Schema::create('tb_keranjang_belanja', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user')->nullable(); // Foreign key to users table
            $table->string('session_id', 255)->nullable();
            $table->unsignedBigInteger('id_seller')->nullable(); // Foreign key to tb_penjual table
            $table->integer('total_items')->default(0);
            $table->decimal('total_berat', 10, 2)->default(0.00);
            $table->decimal('total_harga', 15, 2)->default(0.00);
            $table->decimal('total_diskon', 10, 2)->default(0.00);
            $table->boolean('is_cart_aktif')->default(true);
            $table->timestamp('kadaluarsa_pada')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            
            // Foreign key constraints
            $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');
            $table->foreign('id_seller')->references('id')->on('tb_penjual')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_keranjang_belanja');
    }
};
