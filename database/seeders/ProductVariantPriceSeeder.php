<?php

namespace Database\Seeders;

use App\Models\ProductVariantPrice;
use Illuminate\Database\Seeder;

class ProductVariantPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data for product variant prices
        $variantPrices = [
            [
                'produk_id' => 1,
                'gambar' => null,
                'harga' => 150000,
                'stok' => 20,
                'kode_varian' => 'VARIAN-001',
            ],
            [
                'produk_id' => 1,
                'gambar' => null,
                'harga' => 155000,
                'stok' => 15,
                'kode_varian' => 'VARIAN-002',
            ],
            [
                'produk_id' => 2,
                'gambar' => null,
                'harga' => 85000,
                'stok' => 50,
                'kode_varian' => 'VARIAN-003',
            ],
            [
                'produk_id' => 2,
                'gambar' => null,
                'harga' => 90000,
                'stok' => 30,
                'kode_varian' => 'VARIAN-004',
            ],
        ];

        foreach ($variantPrices as $variantPrice) {
            ProductVariantPrice::create([
                'produk_id' => $variantPrice['produk_id'],
                'gambar' => $variantPrice['gambar'],
                'harga' => $variantPrice['harga'],
                'stok' => $variantPrice['stok'],
                'kode_varian' => $variantPrice['kode_varian'],
            ]);
        }
    }
}