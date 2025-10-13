<?php

namespace Database\Seeders;

use App\Models\ProductShippingInfo;
use Illuminate\Database\Seeder;

class ProductShippingInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data for product shipping info
        $shippingInfos = [
            [
                'id_produk' => 1,
                'id_kota_asal' => 1,
                'nama_kota_asal' => 'Jakarta Pusat',
                'estimasi_pengiriman' => json_encode(['jne' => '1-2 hari', 'pos' => '2-3 hari', 'tiki' => '2-3 hari']),
                'berat_pengiriman' => 0.5,
                'dimensi_pengiriman' => json_encode(['panjang' => 20, 'lebar' => 15, 'tinggi' => 10]),
                'biaya_pengemasan' => 5000,
                'is_gratis_ongkir' => true,
            ],
            [
                'id_produk' => 2,
                'id_kota_asal' => 2,
                'nama_kota_asal' => 'Bandung',
                'estimasi_pengiriman' => json_encode(['jne' => '2-3 hari', 'pos' => '3-5 hari', 'tiki' => '2-4 hari']),
                'berat_pengiriman' => 1.2,
                'dimensi_pengiriman' => json_encode(['panjang' => 30, 'lebar' => 20, 'tinggi' => 15]),
                'biaya_pengemasan' => 10000,
                'is_gratis_ongkir' => false,
            ],
        ];

        foreach ($shippingInfos as $shippingInfo) {
            ProductShippingInfo::create([
                'id_produk' => $shippingInfo['id_produk'],
                'id_kota_asal' => $shippingInfo['id_kota_asal'],
                'nama_kota_asal' => $shippingInfo['nama_kota_asal'],
                'estimasi_pengiriman' => $shippingInfo['estimasi_pengiriman'],
                'berat_pengiriman' => $shippingInfo['berat_pengiriman'],
                'dimensi_pengiriman' => $shippingInfo['dimensi_pengiriman'],
                'biaya_pengemasan' => $shippingInfo['biaya_pengemasan'],
                'is_gratis_ongkir' => $shippingInfo['is_gratis_ongkir'],
            ]);
        }
    }
}