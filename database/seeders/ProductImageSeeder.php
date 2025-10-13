<?php

namespace Database\Seeders;

use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data for product images
        $images = [
            [
                'id_produk' => 1,
                'id_harga_varian' => null,
                'url_gambar' => 'https://example.com/images/product1_main.jpg',
                'alt_text' => 'Gambar utama produk 1',
                'urutan_gambar' => 0,
                'is_gambar_utama' => true,
                'tipe_gambar' => 'GALERI',
            ],
            [
                'id_produk' => 1,
                'id_harga_varian' => null,
                'url_gambar' => 'https://example.com/images/product1_view2.jpg',
                'alt_text' => 'Gambar sisi produk 1',
                'urutan_gambar' => 1,
                'is_gambar_utama' => false,
                'tipe_gambar' => 'GALERI',
            ],
            [
                'id_produk' => 2,
                'id_harga_varian' => null,
                'url_gambar' => 'https://example.com/images/product2_main.jpg',
                'alt_text' => 'Gambar utama produk 2',
                'urutan_gambar' => 0,
                'is_gambar_utama' => true,
                'tipe_gambar' => 'GALERI',
            ],
            [
                'id_produk' => 2,
                'id_harga_varian' => 1,
                'url_gambar' => 'https://example.com/images/product2_variant1.jpg',
                'alt_text' => 'Gambar varian produk 2',
                'urutan_gambar' => 0,
                'is_gambar_utama' => false,
                'tipe_gambar' => 'VARIAN',
            ],
        ];

        foreach ($images as $image) {
            ProductImage::create([
                'id_produk' => $image['id_produk'],
                'id_harga_varian' => $image['id_harga_varian'],
                'url_gambar' => $image['url_gambar'],
                'alt_text' => $image['alt_text'],
                'urutan_gambar' => $image['urutan_gambar'],
                'is_gambar_utama' => $image['is_gambar_utama'],
                'tipe_gambar' => $image['tipe_gambar'],
            ]);
        }
    }
}