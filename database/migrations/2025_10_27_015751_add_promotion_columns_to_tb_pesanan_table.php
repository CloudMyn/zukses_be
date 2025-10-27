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
        Schema::table('tb_pesanan', function (Blueprint $table) {
            $table->string('kode_promosi', 50)->nullable()->after('id_alamat_pengiriman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_pesanan', function (Blueprint $table) {
            $table->dropColumn('kode_promosi');
        });
    }
};
