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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('nomor_telepon')->nullable()->unique();
            $table->string('kata_sandi'); // password
            $table->enum('tipe_user', ['ADMIN', 'PELANGGAN', 'PEDAGANG'])->default('PELANGGAN');
            $table->enum('status', ['AKTIF', 'TIDAK_AKTIF', 'DIBLOKIR', 'SUSPEND'])->default('AKTIF');
            $table->timestamp('email_terverifikasi_pada')->nullable(); // email_verified_at
            $table->timestamp('telepon_terverifikasi_pada')->nullable();
            $table->timestamp('terakhir_login_pada')->nullable();
            $table->string('url_foto_profil')->nullable();
            $table->json('pengaturan')->nullable(); // settings: tema, notifikasi, privacy
            $table->string('nama_depan')->nullable();
            $table->string('nama_belakang')->nullable();
            $table->string('nama_lengkap')->nullable();
            $table->enum('jenis_kelamin', ['LAKI_LAKI', 'PEREMPUAN', 'RAHASIA'])->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('bio')->nullable();
            $table->json('url_media_sosial')->nullable(); // instagram, facebook, twitter
            $table->json('bidang_interests')->nullable(); // categories yang diikuti
            $table->timestamp('dibuat_pada')->nullable(); // created_at
            $table->timestamp('diperbarui_pada')->nullable(); // updated_at
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
