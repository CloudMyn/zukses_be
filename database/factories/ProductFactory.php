<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $seller = Seller::factory()->create();
        $user = User::factory()->create();

        return [
            'id_seller' => $seller->id,
            'id_admin' => $user->id,
            'sku' => $this->faker->unique()->ean13(),
            'nama_produk' => $this->faker->sentence(),
            'slug_produk' => $this->faker->unique()->slug(),
            'deskripsi_lengkap' => $this->faker->paragraphs(3, true),
            'kondisi_produk' => $this->faker->randomElement(['BARU', 'BEKAS']),
            'status_produk' => $this->faker->randomElement(['DRAFT', 'AKTIF', 'NONAKTIF', 'DITANGGUH', 'DITOLAK']),
            'berat_paket' => $this->faker->randomFloat(2, 0.1, 10.0),
            'panjang_paket' => $this->faker->randomFloat(2, 1, 50),
            'lebar_paket' => $this->faker->randomFloat(2, 1, 50),
            'tinggi_paket' => $this->faker->randomFloat(2, 1, 50),
            'harga_minimum' => $this->faker->randomFloat(2, 10000, 50000),
            'harga_maximum' => $this->faker->randomFloat(2, 50000, 200000),
            'jumlah_stok' => $this->faker->numberBetween(0, 100),
            'stok_minimum' => $this->faker->numberBetween(0, 10),
            'jumlah_terjual' => $this->faker->numberBetween(0, 1000),
            'jumlah_dilihat' => $this->faker->numberBetween(0, 10000),
            'jumlah_difavoritkan' => $this->faker->numberBetween(0, 100),
            'rating_produk' => $this->faker->randomFloat(1, 1, 5),
            'jumlah_ulasan' => $this->faker->numberBetween(0, 100),
            'is_produk_unggulan' => $this->faker->boolean(),
            'is_produk_preorder' => $this->faker->boolean(),
            'is_cod' => $this->faker->boolean(),
            'is_approved' => true,
            'is_product_varian' => $this->faker->boolean(),
            'waktu_preorder' => $this->faker->numberBetween(1, 30),
            'garansi_produk' => $this->faker->randomElement(['Tidak Ada', '1 Minggu', '1 Bulan', '3 Bulan', '6 Bulan', '1 Tahun']),
            'etalase_kategori' => json_encode([$this->faker->word(), $this->faker->word()]),
            'tag_produk' => json_encode([$this->faker->word(), $this->faker->word(), $this->faker->word()]),
            'meta_title' => $this->faker->sentence(),
            'meta_description' => $this->faker->sentence(),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}