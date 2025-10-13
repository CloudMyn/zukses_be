<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'produk_id' => Product::factory(),
            'nama_varian' => $this->faker->word(),
            'urutan' => $this->faker->numberBetween(0, 10),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}