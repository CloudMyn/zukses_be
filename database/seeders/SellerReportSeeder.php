<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SellerReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reports = [];
        
        for ($i = 1; $i <= 50; $i++) {
            $reports[] = [
                'id' => $i,
                'id_penjual' => rand(1, 15), // Assuming 15 sellers exist
                'id_admin' => rand(1, 5), // Assuming 5 admin users exist
                'jenis_laporan' => collect(['PENJUALAN', 'PRODUK', 'PERFORMANCE', 'KEUANGAN'])->random(),
                'periode_awal' => now()->subMonths(rand(1, 6))->startOfMonth()->format('Y-m-d'),
                'periode_akhir' => now()->subMonths(rand(0, 5))->endOfMonth()->format('Y-m-d'),
                'data_laporan' => json_encode([
                    'total_penjualan' => rand(1000000, 50000000),
                    'jumlah_transaksi' => rand(10, 500),
                    'jumlah_produk_terjual' => rand(5, 100),
                    'rata_rata_rating' => rand(300, 500) / 100, // 3.00 to 5.00
                ]),
                'ringkasan' => 'Ringkasan laporan penjual #' . $i,
                'tanggal_laporan' => now()->subDays(rand(0, 30))->format('Y-m-d'),
                'status_laporan' => collect(['DRAFT', 'TERKIRIM', 'SELESAI', 'DISETUJUI', 'DITOLAK'])->random(),
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ];
        }

        DB::table('laporan_penjual')->insert($reports);
    }
}