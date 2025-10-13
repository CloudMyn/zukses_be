<?php

namespace Database\Factories;

use App\Models\ProductVariantPrice;
use App\Models\ProductVariantValue;
use App\Models\VariantPriceComposition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VariantPriceComposition>
 */
class VariantPriceCompositionFactory extends Factory
{
    protected $model = VariantPriceComposition::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'harga_varian_id' => ProductVariantPrice::factory(),
            'nilai_varian_id' => ProductVariantValue::factory(),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}