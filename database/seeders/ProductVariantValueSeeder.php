<?php

namespace Database\Seeders;

use App\Models\ProductVariantValue;
use Illuminate\Database\Seeder;

class ProductVariantValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data for product variant values
        $variantValues = [
            [
                'varian_id' => 1,
                'nilai' => 'Merah',
                'urutan' => 1,
            ],
            [
                'varian_id' => 1,
                'nilai' => 'Biru',
                'urutan' => 2,
            ],
            [
                'varian_id' => 1,
                'nilai' => 'Hijau',
                'urutan' => 3,
            ],
            [
                'varian_id' => 2,
                'nilai' => 'S',
                'urutan' => 1,
            ],
            [
                'varian_id' => 2,
                'nilai' => 'M',
                'urutan' => 2,
            ],
            [
                'varian_id' => 2,
                'nilai' => 'L',
                'urutan' => 3,
            ],
            [
                'varian_id' => 2,
                'nilai' => 'XL',
                'urutan' => 4,
            ],
        ];

        foreach ($variantValues as $variantValue) {
            ProductVariantValue::create([
                'varian_id' => $variantValue['varian_id'],
                'nilai' => $variantValue['nilai'],
                'urutan' => $variantValue['urutan'],
            ]);
        }
    }
}