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
            // Make email nullable (keep unique constraint)
            $table->string('email')->nullable()->unique()->change();

            // Make nomor_telepon nullable but remove unique constraint first
            $table->dropUnique('users_nomor_telepon_unique');
            $table->string('nomor_telepon')->nullable()->change();

            // Add unique constraint back for non-null values
            $table->unique('nomor_telepon');
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
