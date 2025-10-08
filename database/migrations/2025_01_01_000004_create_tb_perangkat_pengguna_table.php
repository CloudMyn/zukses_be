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
        Schema::create('perangkat_pengguna', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->string('device_id')->unique();
            $table->enum('device_type', ['MOBILE', 'TABLET', 'DESKTOP', 'TV']);
            $table->string('device_name');
            $table->string('operating_system');
            $table->string('app_version')->nullable();
            $table->string('push_token')->nullable();
            $table->boolean('adalah_device_terpercaya')->default(false);
            $table->timestamp('terakhir_aktif_pada');
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
        Schema::dropIfExists('perangkat_pengguna');
    }
};
