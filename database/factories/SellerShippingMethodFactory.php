<?php

namespace Database\Factories;

use App\Models\SellerShippingMethod;
use App\Models\Seller;
use App\Models\ShippingMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class SellerShippingMethodFactory extends Factory
{
    protected $model = SellerShippingMethod::class;

    public function definition(): array
    {
        $seller = Seller::factory()->create();
        $shippingMethod = ShippingMethod::factory()->create();

        return [
            'id_seller' => $seller->id,
            'id_metode_pengiriman' => $shippingMethod->id,
            'is_aktif' => $this->faker->boolean(80),
            'biaya_tambahan' => $this->faker->randomFloat(2, 0, 50000),
            'estimasi_pengiriman' => $this->faker->randomElement(['1-2 hari', '2-3 hari', '3-5 hari', '1 minggu']),
            'catatan_pengiriman' => $this->faker->optional()->sentence(),
            'konfigurasi_metode' => json_encode([
                'min_berat' => $this->faker->randomFloat(2, 0, 1),
                'max_berat' => $this->faker->randomFloat(2, 1, 100),
                'min_nilai' => $this->faker->randomFloat(2, 0, 10000),
                'max_nilai' => $this->faker->randomFloat(2, 10000, 1000000),
            ]),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}