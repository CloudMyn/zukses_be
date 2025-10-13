<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariantPrice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariantPrice>
 */
class ProductVariantPriceFactory extends Factory
{
    protected $model = ProductVariantPrice::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'produk_id' => Product::factory(),
            'gambar' => $this->faker->optional()->imageUrl(),
            'harga' => $this->faker->randomElement([10000, 15000, 20000, 25000, 30000, 50000, 75000, 100000]),
            'stok' => $this->faker->numberBetween(0, 100),
            'kode_varian' => $this->faker->optional()->bothify('VAR-????-####'),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}