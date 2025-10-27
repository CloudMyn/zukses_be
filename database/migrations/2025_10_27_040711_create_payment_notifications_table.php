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
        Schema::create('payment_notifications', function (Blueprint $table) {
            $table->id();

            // Informasi dasar
            $table->string('notification_id', 100)->unique()->comment('ID unik notifikasi');
            $table->string('transaction_id', 100)->comment('ID transaksi terkait');
            $table->string('order_id', 100)->comment('ID order dari Midtrans');
            $table->string('transaction_status', 50)->comment('Status transaksi dari Midtrans');
            $table->string('payment_type', 50)->nullable()->comment('Tipe pembayaran');
            $table->string('signature_key', 255)->comment('Signature key dari Midtrans');

            // Informasi pembayaran
            $table->decimal('gross_amount', 15, 2)->comment('Total jumlah pembayaran');
            $table->string('payment_code', 100)->nullable()->comment('Kode pembayaran untuk VA');
            $table->string('approval_code', 100)->nullable()->comment('Kode approval');
            $table->string('bank', 50)->nullable()->comment('Bank untuk pembayaran');
            $table->string('va_number', 50)->nullable()->comment('Nomor VA');

            // Informasi waktu
            $table->timestamp('transaction_time')->comment('Waktu transaksi dari Midtrans');
            $table->timestamp('settlement_time')->nullable()->comment('Waktu settlement');
            $table->timestamp('expiry_time')->nullable()->comment('Waktu kadaluarsa');

            // Status notifikasi
            $table->enum('processing_status', [
                'received', 'processing', 'processed', 'failed', 'duplicate'
            ])->default('received')->comment('Status pemrosesan notifikasi');
            $table->string('error_message', 255)->nullable()->comment('Pesan error jika gagal');

            // Payload notifikasi
            $table->json('raw_payload')->comment('Raw payload dari Midtrans');
            $table->json('processed_data')->nullable()->comment('Data yang sudah diproses');
            $table->json('response_data')->nullable()->comment('Response yang dikirim ke Midtrans');

            // Informasi sistem
            $table->string('ip_address', 45)->comment('IP address sumber notifikasi');
            $table->string('user_agent', 255)->nullable()->comment('User agent');
            $table->integer('retry_count')->default(0)->comment('Jumlah retry yang sudah dilakukan');
            $table->timestamp('last_retry_at')->nullable()->comment('Waktu retry terakhir');

            // Timestamps
            $table->timestamp('received_at')->useCurrent()->comment('Waktu notifikasi diterima');
            $table->timestamp('processed_at')->nullable()->comment('Waktu notifikasi diproses');
            $table->timestamps();

            // Indexes
            $table->index(['notification_id']);
            $table->index(['transaction_id']);
            $table->index(['order_id']);
            $table->index(['transaction_status']);
            $table->index(['payment_type']);
            $table->index(['processing_status']);
            $table->index(['received_at']);
            $table->index(['processed_at']);

            // Composite indexes
            $table->index(['transaction_id', 'processing_status']);
            $table->index(['order_id', 'transaction_status']);
            $table->index(['processing_status', 'received_at']);

            // Unique index untuk prevent duplicate notifications
            $table->unique(['transaction_id', 'transaction_status', 'signature_key'], 'unique_notification');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_notifications');
    }
};
