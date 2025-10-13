<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'id' => 1,
                'nama_pembayaran' => 'Bank Transfer',
                'tipe_pembayaran' => 'TRANSFER_BANK',
                'provider_pembayaran' => 'BCA',
                'logo_pembayaran' => 'bank_transfer.png',
                'deskripsi_pembayaran' => 'Pembayaran melalui transfer antar bank',
                'biaya_admin_percent' => 0.5,
                'biaya_admin_fixed' => 0,
                'minimum_pembayaran' => 10000,
                'maksimum_pembayaran' => 100000000,
                'is_aktif' => true,
                'urutan_tampilan' => 1,
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ],
            [
                'id' => 2,
                'nama_pembayaran' => 'Credit Card',
                'tipe_pembayaran' => 'CREDIT_CARD',
                'provider_pembayaran' => 'Visa',
                'logo_pembayaran' => 'credit_card.png',
                'deskripsi_pembayaran' => 'Pembayaran menggunakan kartu kredit',
                'biaya_admin_percent' => 2.5,
                'biaya_admin_fixed' => 0,
                'minimum_pembayaran' => 50000,
                'maksimum_pembayaran' => 50000000,
                'is_aktif' => true,
                'urutan_tampilan' => 2,
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ],
            [
                'id' => 3,
                'nama_pembayaran' => 'E-Wallet',
                'tipe_pembayaran' => 'E_WALLET',
                'provider_pembayaran' => 'OVO',
                'logo_pembayaran' => 'e_wallet.png',
                'deskripsi_pembayaran' => 'Pembayaran menggunakan dompet digital',
                'biaya_admin_percent' => 1.0,
                'biaya_admin_fixed' => 0,
                'minimum_pembayaran' => 5000,
                'maksimum_pembayaran' => 10000000,
                'is_aktif' => true,
                'urutan_tampilan' => 3,
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ],
            [
                'id' => 4,
                'nama_pembayaran' => 'Cash on Delivery',
                'tipe_pembayaran' => 'COD',
                'provider_pembayaran' => 'COD',
                'logo_pembayaran' => 'cod.png',
                'deskripsi_pembayaran' => 'Pembayaran saat barang diterima',
                'biaya_admin_percent' => 0,
                'biaya_admin_fixed' => 0,
                'minimum_pembayaran' => 0,
                'maksimum_pembayaran' => 1000000,
                'is_aktif' => true,
                'urutan_tampilan' => 4,
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ],
        ];

        DB::table('metode_pembayaran')->insert($paymentMethods);
    }
}