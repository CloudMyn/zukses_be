<?php

namespace Database\Factories;

use App\Models\InventoryLog;
use App\Models\Product;
use App\Models\ProductVariantPrice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InventoryLog>
 */
class InventoryLogFactory extends Factory
{
    protected $model = InventoryLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jumlah_transaksi = $this->faker->numberBetween(1, 100);
        $stok_sebelum = $this->faker->numberBetween(0, 200);
        $tipe_transaksi = $this->faker->randomElement(['MASUK', 'KELUAR', 'PENYESUAIAN', 'RUSAK', 'KEMBALI']);
        
        // Calculate stok_sesudah based on transaction type
        $stok_sesudah = $stok_sebelum;
        if ($tipe_transaksi === 'MASUK') {
            $stok_sesudah = $stok_sebelum + $jumlah_transaksi;
        } elseif ($tipe_transaksi === 'KELUAR') {
            $stok_sesudah = max(0, $stok_sebelum - $jumlah_transaksi);
        } elseif ($tipe_transaksi === 'PENYESUAIAN') {
            $stok_sesudah = $stok_sebelum + $this->faker->numberBetween(-50, 50);
        } elseif ($tipe_transaksi === 'RUSAK') {
            $stok_sesudah = max(0, $stok_sebelum - $jumlah_transaksi);
        } elseif ($tipe_transaksi === 'KEMBALI') {
            $stok_sesudah = $stok_sebelum + $jumlah_transaksi;
        }
        
        return [
            'id_produk' => Product::factory(),
            'id_harga_varian' => $this->faker->optional()->randomElement(ProductVariantPrice::pluck('id')->toArray()),
            'tipe_transaksi' => $tipe_transaksi,
            'jumlah_transaksi' => $jumlah_transaksi,
            'stok_sebelum' => $stok_sebelum,
            'stok_sesudah' => $stok_sesudah,
            'alasan_transaksi' => $this->faker->optional()->sentence(),
            'id_operator' => User::factory(),
            'catatan_tambahan' => $this->faker->optional()->paragraph(),
            'dibuat_pada' => now(),
        ];
    }
}