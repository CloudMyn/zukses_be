<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reviews = [];
        
        for ($i = 1; $i <= 200; $i++) {
            $reviews[] = [
                'id' => $i,
                'id_produk' => rand(1, 50), // Assuming 50 products exist
                'id_user' => rand(1, 100), // Assuming 100 users exist
                'id_order_item' => rand(1, 150), // Assuming 150 order items exist
                'id_varian_produk' => rand(1, 30), // Assuming 30 product variants exist
                'rating' => rand(1, 5),
                'judul_ulasan' => 'Ulasan produk #' . $i,
                'isi_ulasan' => 'Produk ini sangat bagus dan sesuai dengan ekspektasi saya.',
                'is_verified_purchase' => rand(0, 1),
                'is_rekomendasi' => rand(0, 1),
                'jumlah_suka' => rand(0, 50),
                'jumlah_tidak_suka' => rand(0, 10),
                'tanggal_ulasan' => now()->subDays(rand(0, 90))->format('Y-m-d H:i:s'),
                'status_ulasan' => collect(['AKTIF', 'TIDAK_AKTIF', 'DITOLAK'])->random(),
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ];
        }

        DB::table('ulasan_produk')->insert($reviews);
    }
}