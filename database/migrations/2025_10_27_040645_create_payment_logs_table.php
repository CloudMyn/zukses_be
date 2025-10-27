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
        Schema::create('payment_logs', function (Blueprint $table) {
            $table->id();

            // Informasi dasar
            $table->string('transaction_id', 100)->comment('ID transaksi terkait');
            $table->unsignedBigInteger('payment_transaction_id')->nullable()->comment('ID payment transaction terkait');
            $table->unsignedBigInteger('user_id')->nullable()->comment('ID user terkait');
            $table->unsignedBigInteger('pesanan_id')->nullable()->comment('ID pesanan terkait');

            // Informasi log
            $table->enum('log_type', [
                'request', 'response', 'webhook', 'callback',
                'notification', 'error', 'info', 'debug'
            ])->comment('Tipe log');
            $table->enum('log_level', ['debug', 'info', 'warning', 'error', 'critical'])
                  ->default('info')->comment('Level log');
            $table->string('event_name', 100)->nullable()->comment('Nama event yang terjadi');
            $table->string('action', 100)->comment('Aksi yang dilakukan');

            // Informasi request/response
            $table->string('endpoint', 255)->nullable()->comment('Endpoint yang diakses');
            $table->string('method', 10)->nullable()->comment('HTTP method');
            $table->string('status_code', 10)->nullable()->comment('HTTP status code');
            $table->text('message')->comment('Pesan log');
            $table->json('request_data')->nullable()->comment('Data request dalam format JSON');
            $table->json('response_data')->nullable()->comment('Data response dalam format JSON');
            $table->json('headers')->nullable()->comment('HTTP headers dalam format JSON');

            // Informasi performa
            $table->integer('execution_time_ms')->nullable()->comment('Waktu eksekusi dalam milisecond');
            $table->integer('memory_usage_mb')->nullable()->comment('Penggunaan memory dalam MB');

            // Informasi error
            $table->string('error_code', 50)->nullable()->comment('Kode error');
            $table->string('error_message', 255)->nullable()->comment('Pesan error');
            $table->text('stack_trace')->nullable()->comment('Stack trace untuk debugging');

            // Informasi sistem
            $table->string('ip_address', 45)->nullable()->comment('IP address sumber');
            $table->string('user_agent', 255)->nullable()->comment('User agent');
            $table->string('session_id', 100)->nullable()->comment('Session ID');
            $table->string('request_id', 100)->nullable()->comment('Request ID untuk tracing');

            // Metadata tambahan
            $table->json('metadata')->nullable()->comment('Metadata tambahan dalam format JSON');
            $table->json('context')->nullable()->comment('Context informasi');

            // Timestamps
            $table->timestamp('logged_at')->useCurrent()->comment('Waktu log dibuat');
            $table->timestamps();

            // Indexes
            $table->index(['transaction_id']);
            $table->index(['payment_transaction_id']);
            $table->index(['user_id']);
            $table->index(['pesanan_id']);
            $table->index(['log_type']);
            $table->index(['log_level']);
            $table->index(['action']);
            $table->index(['endpoint']);
            $table->index(['logged_at']);
            $table->index(['created_at']);

            // Composite indexes untuk query yang sering digunakan
            $table->index(['transaction_id', 'log_type']);
            $table->index(['payment_transaction_id', 'log_level']);
            $table->index(['user_id', 'logged_at']);
            $table->index(['log_level', 'logged_at']);

            // Foreign keys
            $table->foreign('payment_transaction_id')->references('id')->on('payment_transactions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('pesanan_id')->references('id')->on('tb_pesanan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_logs');
    }
};
