<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Product;
use App\Models\ProductShippingInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductShippingInfo>
 */
class ProductShippingInfoFactory extends Factory
{
    protected $model = ProductShippingInfo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_produk' => Product::factory(),
            'id_kota_asal' => City::factory(),
            'nama_kota_asal' => $this->faker->city(),
            'estimasi_pengiriman' => [
                'jne' => $this->faker->randomElement(['1-2 hari', '2-3 hari', '3-5 hari']),
                'pos' => $this->faker->randomElement(['2-3 hari', '3-5 hari', '5-7 hari']),
                'tiki' => $this->faker->randomElement(['1-3 hari', '3-5 hari', '5-7 hari']),
            ],
            'berat_pengiriman' => $this->faker->randomFloat(2, 0.1, 50.0),
            'dimensi_pengiriman' => [
                'panjang' => $this->faker->randomElement([10, 15, 20, 25, 30]),
                'lebar' => $this->faker->randomElement([5, 10, 15, 20]),
                'tinggi' => $this->faker->randomElement([5, 10, 15, 20]),
            ],
            'biaya_pengemasan' => $this->faker->randomFloat(2, 0, 15000),
            'is_gratis_ongkir' => $this->faker->boolean(30), // 30% chance to be true
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}