<?php

namespace Database\Factories;

use App\Models\ProductVariantPrice;
use App\Models\VariantShippingInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VariantShippingInfo>
 */
class VariantShippingInfoFactory extends Factory
{
    protected $model = VariantShippingInfo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'harga_varian_id' => ProductVariantPrice::factory(),
            'berat' => $this->faker->randomFloat(2, 0.1, 50.0),
            'panjang' => $this->faker->randomFloat(2, 1.0, 100.0),
            'lebar' => $this->faker->randomFloat(2, 1.0, 100.0),
            'tinggi' => $this->faker->randomFloat(2, 1.0, 100.0),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}