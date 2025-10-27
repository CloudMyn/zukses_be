<?php

namespace Database\Seeders;

use App\Models\Promosi;
use App\Models\ProdukPromosi;
use App\Models\RiwayatPenggunaanPromosi;
use Illuminate\Database\Seeder;

class PromosiSeeder extends Seeder
{
    public function run()
    {
        // Get or create an admin user to be the creator
        $adminUser = \App\Models\User::where('tipe_user', 'ADMIN')->first();
        if (!$adminUser) {
            $adminUser = \App\Models\User::factory()->create([
                'tipe_user' => 'ADMIN',
                'username' => 'admin_promo',
                'email' => 'admin@promo.test',
            ]);
        }

        // Buat promosi contoh
        $promoList = [
            [
                'kode_promosi' => 'WELCOME10',
                'nama_promosi' => 'Promo Selamat Datang',
                'deskripsi' => 'Diskon 10% untuk pengguna baru',
                'jenis_promosi' => 'KODE_PROMOSI',
                'tipe_diskon' => 'PERSEN',
                'nilai_diskon' => 10,
                'jumlah_maksimum_penggunaan' => 1000,
                'jumlah_maksimum_penggunaan_per_pengguna' => 1,
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addDays(30),
                'minimum_pembelian' => 50000,
                'dapat_digabungkan' => false,
                'status_aktif' => true,
                'id_pembuat' => $adminUser->id,
            ],
            [
                'kode_promosi' => 'BULANAN20',
                'nama_promosi' => 'Promo Bulanan',
                'deskripsi' => 'Diskon 20% untuk produk elektronik',
                'jenis_promosi' => 'KELOMPOK_PRODUK',
                'tipe_diskon' => 'PERSEN',
                'nilai_diskon' => 20,
                'jumlah_maksimum_penggunaan' => 500,
                'jumlah_maksimum_penggunaan_per_pengguna' => 2,
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addDays(14),
                'minimum_pembelian' => 100000,
                'dapat_digabungkan' => true,
                'status_aktif' => true,
                'id_pembuat' => $adminUser->id,
            ],
            [
                'kode_promosi' => 'FREESHIP50',
                'nama_promosi' => 'Gratis Ongkir',
                'deskripsi' => 'Gratis ongkir untuk pembelian di atas Rp50.000',
                'jenis_promosi' => 'OTOMATIS',
                'tipe_diskon' => 'NOMINAL',
                'nilai_diskon' => 20000,
                'jumlah_maksimum_penggunaan' => 2000,
                'jumlah_maksimum_penggunaan_per_pengguna' => 10,
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addDays(7),
                'minimum_pembelian' => 50000,
                'dapat_digabungkan' => true,
                'status_aktif' => true,
                'id_pembuat' => $adminUser->id,
            ]
        ];

        foreach ($promoList as $promoData) {
            $promosi = Promosi::create($promoData);
        }

        // Buat beberapa relasi produk-promo - first create some products if needed
        $productCount = \App\Models\Product::count();
        if ($productCount == 0) {
            \App\Models\Product::factory(10)->create();
        }
        
        // Then associate some products with promotions
        ProdukPromosi::factory(20)->create();
    }
}