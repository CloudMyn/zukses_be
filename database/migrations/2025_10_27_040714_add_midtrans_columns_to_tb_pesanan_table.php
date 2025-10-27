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
            // Kolom Midtrans untuk integrasi payment
            $table->string('midtrans_order_id', 100)->nullable()->after('status_pembayaran')
                  ->comment('Order ID dari Midtrans untuk integrasi payment');
            $table->string('midtrans_transaction_id', 100)->nullable()->after('midtrans_order_id')
                  ->comment('Transaction ID dari Midtrans');
            $table->string('midtrans_payment_type', 50)->nullable()->after('midtrans_transaction_id')
                  ->comment('Tipe pembayaran dari Midtrans');
            $table->string('midtrans_signature_key', 255)->nullable()->after('midtrans_payment_type')
                  ->comment('Signature key untuk validasi webhook');
            $table->decimal('midtrans_fee', 10, 2)->default(0)->after('total_pembayaran')
                  ->comment('Biaya transaksi Midtrans');
            $table->decimal('midtrans_tax', 10, 2)->default(0)->after('midtrans_fee')
                  ->comment('Pajak transaksi Midtrans');
            $table->timestamp('midtrans_transaction_time')->nullable()->after('tanggal_dibayar')
                  ->comment('Waktu transaksi Midtrans');
            $table->timestamp('midtrans_settlement_time')->nullable()->after('midtrans_transaction_time')
                  ->comment('Waktu settlement Midtrans');
            $table->timestamp('midtrans_expiry_time')->nullable()->after('midtrans_settlement_time')
                  ->comment('Waktu kadaluarsa pembayaran');
            $table->json('midtransaction_details')->nullable()->after('alasan_pembatalan')
                  ->comment('Detail transaksi Midtrans dalam format JSON');
            $table->text('midtrans_error_message')->nullable()->after('midtransaction_details')
                  ->comment('Pesan error dari Midtrans');

            // Indexes untuk optimasi query
            $table->index(['midtrans_order_id']);
            $table->index(['midtrans_transaction_id']);
            $table->index(['midtrans_payment_type']);
            $table->index(['midtrans_transaction_time']);
            $table->index(['midtrans_expiry_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_pesanan', function (Blueprint $table) {
            // Drop kolom Midtrans
            $table->dropColumn([
                'midtrans_order_id',
                'midtrans_transaction_id',
                'midtrans_payment_type',
                'midtrans_signature_key',
                'midtrans_fee',
                'midtrans_tax',
                'midtrans_transaction_time',
                'midtrans_settlement_time',
                'midtrans_expiry_time',
                'midtransaction_details',
                'midtrans_error_message'
            ]);
        });
    }
};
