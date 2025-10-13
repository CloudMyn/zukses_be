<?php

namespace Database\Seeders;

use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data for product variants
        $variants = [
            [
                'produk_id' => 1,
                'nama_varian' => 'Warna',
                'urutan' => 1,
            ],
            [
                'produk_id' => 1,
                'nama_varian' => 'Ukuran',
                'urutan' => 2,
            ],
            [
                'produk_id' => 2,
                'nama_varian' => 'Ukuran',
                'urutan' => 1,
            ],
            [
                'produk_id' => 2,
                'nama_varian' => 'Model',
                'urutan' => 2,
            ],
        ];

        foreach ($variants as $variant) {
            ProductVariant::create([
                'produk_id' => $variant['produk_id'],
                'nama_varian' => $variant['nama_varian'],
                'urutan' => $variant['urutan'],
            ]);
        }
    }
}