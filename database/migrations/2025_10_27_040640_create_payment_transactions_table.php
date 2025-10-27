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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();

            // Informasi transaksi
            $table->string('transaction_id', 100)->unique()->comment('ID transaksi unik dari sistem');
            $table->string('order_number', 50)->comment('Nomor pesanan terkait');
            $table->unsignedBigInteger('user_id')->comment('ID user yang melakukan transaksi');
            $table->unsignedBigInteger('pesanan_id')->nullable()->comment('ID pesanan terkait');

            // Informasi pembayaran
            $table->string('midtrans_transaction_id', 100)->nullable()->comment('ID transaksi dari Midtrans');
            $table->string('midtrans_order_id', 100)->nullable()->comment('ID order dari Midtrans');
            $table->string('payment_type', 50)->nullable()->comment('Tipe pembayaran: credit_card, bank_transfer, dll');
            $table->string('payment_channel', 50)->nullable()->comment('Channel pembayaran: mandiri, bca, gopay, dll');
            $table->string('bank', 50)->nullable()->comment('Bank untuk VA');
            $table->string('va_number', 50)->nullable()->comment('Nomor Virtual Account');
            $table->string('bill_key', 50)->nullable()->comment('Bill key untuk E-Channel');
            $table->string('biller_code', 20)->nullable()->comment('Biller code untuk E-Channel');

            // Informasi jumlah
            $table->decimal('gross_amount', 15, 2)->comment('Total jumlah pembayaran');
            $table->decimal('tax_amount', 15, 2)->default(0)->comment('Jumlah pajak');
            $table->decimal('fee_amount', 15, 2)->default(0)->comment('Biaya transaksi');
            $table->decimal('net_amount', 15, 2)->comment('Jumlah bersih setelah pajak dan fee');

            // Status transaksi
            $table->enum('transaction_status', [
                'pending', 'authorize', 'capture', 'settlement',
                'deny', 'cancel', 'expire', 'refund', 'partial_refund', 'failure'
            ])->default('pending')->comment('Status transaksi');
            $table->enum('fraud_status', ['accept', 'challenge', 'deny'])->nullable()->comment('Status fraud');
            $table->string('status_message', 255)->nullable()->comment('Pesan status dari Midtrans');

            // Informasi waktu
            $table->timestamp('transaction_time')->nullable()->comment('Waktu transaksi dibuat');
            $table->timestamp('settlement_time')->nullable()->comment('Waktu pembayaran berhasil');
            $table->timestamp('expiry_time')->nullable()->comment('Waktu kadaluarsa pembayaran');
            $table->timestamp('paid_at')->nullable()->comment('Waktu pembayaran dilakukan');

            // Informasi kartu kredit (jika ada)
            $table->string('card_type', 50)->nullable()->comment('Tipe kartu: credit, debit');
            $table->string('card_number', 20)->nullable()->comment('4 digit terakhir nomor kartu');
            $table->string('card_token', 100)->nullable()->comment('Token kartu untuk recurring');
            $table->string('approval_code', 50)->nullable()->comment('Kode approval bank');

            // Informasi refund
            $table->decimal('refund_amount', 15, 2)->default(0)->comment('Jumlah refund');
            $table->string('refund_reason', 255)->nullable()->comment('Alasan refund');
            $table->timestamp('refunded_at')->nullable()->comment('Waktu refund dilakukan');

            // Metadata tambahan
            $table->json('customer_details')->nullable()->comment('Detail customer dalam format JSON');
            $table->json('item_details')->nullable()->comment('Detail item dalam format JSON');
            $table->json('custom_field')->nullable()->comment('Custom fields tambahan');
            $table->json('midtrans_response')->nullable()->comment('Response lengkap dari Midtrans');

            // Informasi sistem
            $table->string('ip_address', 45)->nullable()->comment('IP address pembeli');
            $table->string('user_agent', 255)->nullable()->comment('User agent pembeli');
            $table->string('request_id', 100)->nullable()->comment('ID request untuk idempotency');

            // Soft delete
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index(['transaction_id']);
            $table->index(['order_number']);
            $table->index(['user_id']);
            $table->index(['pesanan_id']);
            $table->index(['midtrans_transaction_id']);
            $table->index(['transaction_status']);
            $table->index(['payment_type']);
            $table->index(['transaction_time']);
            $table->index(['paid_at']);
            $table->index(['created_at']);

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pesanan_id')->references('id')->on('tb_pesanan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
