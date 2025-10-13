<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use App\Models\ProductVariantValue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariantValue>
 */
class ProductVariantValueFactory extends Factory
{
    protected $model = ProductVariantValue::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'varian_id' => ProductVariant::factory(),
            'nilai' => $this->faker->word(),
            'urutan' => $this->faker->numberBetween(0, 10),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}