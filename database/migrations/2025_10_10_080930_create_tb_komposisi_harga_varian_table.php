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
        Schema::create('tb_komposisi_harga_varian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('harga_varian_id');
            $table->unsignedBigInteger('nilai_varian_id');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();

            $table->index('harga_varian_id');
            $table->index('nilai_varian_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_komposisi_harga_varian');
    }
};