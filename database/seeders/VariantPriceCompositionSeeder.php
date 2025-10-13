<?php

namespace Database\Seeders;

use App\Models\VariantPriceComposition;
use Illuminate\Database\Seeder;

class VariantPriceCompositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data for variant price compositions
        $compositions = [
            [
                'harga_varian_id' => 1,
                'nilai_varian_id' => 1, // Merah
            ],
            [
                'harga_varian_id' => 1,
                'nilai_varian_id' => 4, // S
            ],
            [
                'harga_varian_id' => 2,
                'nilai_varian_id' => 1, // Merah
            ],
            [
                'harga_varian_id' => 2,
                'nilai_varian_id' => 5, // M
            ],
            [
                'harga_varian_id' => 3,
                'nilai_varian_id' => 2, // Biru
            ],
            [
                'harga_varian_id' => 3,
                'nilai_varian_id' => 4, // S
            ],
            [
                'harga_varian_id' => 4,
                'nilai_varian_id' => 2, // Biru
            ],
            [
                'harga_varian_id' => 4,
                'nilai_varian_id' => 5, // M
            ],
        ];

        foreach ($compositions as $composition) {
            VariantPriceComposition::create([
                'harga_varian_id' => $composition['harga_varian_id'],
                'nilai_varian_id' => $composition['nilai_varian_id'],
            ]);
        }
    }
}