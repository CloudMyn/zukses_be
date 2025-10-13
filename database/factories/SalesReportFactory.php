<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SalesReport>
 */
class SalesReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_penjual' => $this->faker->numberBetween(1, 20), // Assuming there are 20 sellers
            'id_admin' => $this->faker->numberBetween(1, 10), // Assuming there are 10 admin users
            'jenis_laporan' => $this->faker->randomElement(['HARIAN', 'MINGGUAN', 'BULANAN', 'TAHUNAN']),
            'periode_awal' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'periode_akhir' => $this->faker->dateTimeBetween('now', '+1 month'),
            'data_laporan' => json_encode([
                'total_penjualan' => $this->faker->randomFloat(2, 1000000, 100000000),
                'jumlah_transaksi' => $this->faker->numberBetween(10, 1000),
                'jumlah_produk_terjual' => $this->faker->numberBetween(5, 500),
                'rata_rata_harga_produk' => $this->faker->randomFloat(2, 50000, 500000),
                'top_produk' => [
                    ['id_produk' => 1, 'nama_produk' => 'Produk A', 'jumlah_terjual' => 100],
                    ['id_produk' => 2, 'nama_produk' => 'Produk B', 'jumlah_terjual' => 80],
                ],
            ]),
            'ringkasan' => $this->faker->paragraph,
            'tanggal_laporan' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'status_laporan' => $this->faker->randomElement(['DRAFT', 'TERKIRIM', 'SELESAI', 'DISETUJUI', 'DITOLAK']),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}