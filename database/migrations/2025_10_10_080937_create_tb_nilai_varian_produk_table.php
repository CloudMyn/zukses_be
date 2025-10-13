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
        Schema::create('tb_nilai_varian_produk', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('varian_id');
            $table->string('nilai', 255);
            $table->integer('urutan')->default(0);
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('varian_id')->references('id')->on('tb_varian_produk')->onDelete('cascade');
            $table->index('varian_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_nilai_varian_produk');
    }
};