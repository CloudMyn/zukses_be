<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tb_produk_promosi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_promosi');
            $table->unsignedBigInteger('id_produk');
            $table->timestamp('dibuat_pada')->nullable();
            
            // Foreign Keys will be added in a separate migration after all tables exist
            // $table->foreign('id_promosi')->references('id')->on('tb_promosi')->onDelete('cascade');
            // $table->foreign('id_produk')->references('id')->on('tb_produk')->onDelete('cascade');
            
            // Unique constraint to prevent duplicate promo-product combination
            $table->unique(['id_promosi', 'id_produk']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tb_produk_promosi');
    }
};