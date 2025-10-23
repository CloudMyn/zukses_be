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
            // Add the contact column (used for login in the auth tests)
            $table->string('contact')->nullable()->after('nomor_telepon');
            
            // Add password column (to maintain compatibility with some tests)
            // Since the project already uses kata_sandi, we'll make this nullable
            $table->string('password')->nullable()->after('kata_sandi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['contact', 'password']);
        });
    }
};
