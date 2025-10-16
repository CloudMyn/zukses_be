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
        Schema::table('users', function (Blueprint $table) {
            // Make email nullable (the unique constraint already exists)
            $table->string('email')->nullable()->change();

            // Make nomor_telepon nullable (the unique constraint already exists)
            $table->string('nomor_telepon')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Make email required again
            $table->string('email')->nullable(false)->change();

            // Make nomor_telepon required again
            $table->string('nomor_telepon')->nullable(false)->change();
        });
    }
};
