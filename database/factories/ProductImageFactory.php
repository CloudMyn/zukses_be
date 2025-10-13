<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariantPrice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_produk' => Product::factory(),
            'id_harga_varian' => $this->faker->optional()->randomElement(ProductVariantPrice::pluck('id')->toArray()),
            'url_gambar' => $this->faker->imageUrl(),
            'alt_text' => $this->faker->optional()->sentence(),
            'urutan_gambar' => $this->faker->numberBetween(0, 10),
            'is_gambar_utama' => $this->faker->boolean(20), // 20% chance to be true
            'tipe_gambar' => $this->faker->randomElement(['GALERI', 'DESKRIPSI', 'VARIAN']),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}