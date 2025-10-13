<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactions = [];
        
        for ($i = 1; $i <= 50; $i++) {
            $transactions[] = [
                'id' => $i,
                'id_pesanan' => rand(1, 30), // Assuming 30 orders exist
                'id_metode_pembayaran' => rand(1, 4),
                'reference_id' => 'REF-' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'jumlah_pembayaran' => rand(50000, 5000000),
                'status_transaksi' => collect(['MENUNGGU', 'BERHASIL', 'GAGAL', 'KADALUARSA'])->random(),
                'channel_pembayaran' => collect(['BCA', 'GOPAY', 'OVO', 'DANA', 'Credit Card'])->random(),
                'va_number' => rand(0, 1) ? '8888' . str_pad(rand(1, 9999), 8, '0', STR_PAD_LEFT) : null,
                'qr_code' => rand(0, 1) ? 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=payment_' . $i : null,
                'deep_link' => rand(0, 1) ? 'https://payment.example.com/pay/' . $i : null,
                'tanggal_kadaluarsa' => rand(0, 1) ? now()->addDays(rand(1, 7))->format('Y-m-d H:i:s') : null,
                'tanggal_bayar' => rand(0, 1) ? now()->subDays(rand(0, 30))->format('Y-m-d H:i:s') : null,
                'response_gateway' => json_encode([
                    'payment_gateway' => collect(['Midtrans', 'Xendit', 'Doku'])->random(),
                    'payment_type' => collect(['credit_card', 'bank_transfer', 'ewallet', 'cod'])->random(),
                    'payment_code' => 'CODE-' . rand(1000, 9999),
                ]),
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ];
        }

        DB::table('transaksi_pembayaran')->insert($transactions);
    }
}