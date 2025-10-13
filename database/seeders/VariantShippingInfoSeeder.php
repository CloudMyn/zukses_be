<?php

namespace Database\Seeders;

use App\Models\VariantShippingInfo;
use Illuminate\Database\Seeder;

class VariantShippingInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data for variant shipping information
        $shippingInfos = [
            [
                'harga_varian_id' => 1,
                'berat' => 0.5,
                'panjang' => 10.0,
                'lebar' => 8.0,
                'tinggi' => 5.0,
            ],
            [
                'harga_varian_id' => 2,
                'berat' => 0.8,
                'panjang' => 15.0,
                'lebar' => 12.0,
                'tinggi' => 7.0,
            ],
            [
                'harga_varian_id' => 3,
                'berat' => 0.3,
                'panjang' => 8.0,
                'lebar' => 6.0,
                'tinggi' => 4.0,
            ],
            [
                'harga_varian_id' => 4,
                'berat' => 1.2,
                'panjang' => 20.0,
                'lebar' => 15.0,
                'tinggi' => 10.0,
            ],
        ];

        foreach ($shippingInfos as $shippingInfo) {
            VariantShippingInfo::create([
                'harga_varian_id' => $shippingInfo['harga_varian_id'],
                'berat' => $shippingInfo['berat'],
                'panjang' => $shippingInfo['panjang'],
                'lebar' => $shippingInfo['lebar'],
                'tinggi' => $shippingInfo['tinggi'],
            ]);
        }
    }
}