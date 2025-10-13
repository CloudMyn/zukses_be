<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesReportSeeder extends Seeder
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
                'jenis_laporan' => collect(['HARIAN', 'MINGGUAN', 'BULANAN', 'TAHUNAN'])->random(),
                'periode_awal' => now()->subMonths(rand(1, 6))->startOfMonth()->format('Y-m-d'),
                'periode_akhir' => now()->subMonths(rand(0, 5))->endOfMonth()->format('Y-m-d'),
                'data_laporan' => json_encode([
                    'total_penjualan' => rand(1000000, 50000000),
                    'jumlah_transaksi' => rand(10, 500),
                    'jumlah_produk_terjual' => rand(5, 100),
                    'rata_rata_harga_produk' => rand(50000, 500000),
                    'top_produk' => [
                        ['id_produk' => 1, 'nama_produk' => 'Produk A', 'jumlah_terjual' => rand(50, 150)],
                        ['id_produk' => 2, 'nama_produk' => 'Produk B', 'jumlah_terjual' => rand(30, 120)],
                    ],
                ]),
                'ringkasan' => 'Ringkasan laporan penjualan #' . $i,
                'tanggal_laporan' => now()->subDays(rand(0, 30))->format('Y-m-d'),
                'status_laporan' => collect(['DRAFT', 'TERKIRIM', 'SELESAI', 'DISETUJUI', 'DITOLAK'])->random(),
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ];
        }

        DB::table('laporan_penjualan')->insert($reports);
    }
}