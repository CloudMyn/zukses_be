<?php

namespace Database\Factories;

use App\Models\Promosi;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProdukPromosiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_promosi' => Promosi::factory(),
            'id_produk' => Product::factory(),
            'dibuat_pada' => now(),
        ];
    }
}