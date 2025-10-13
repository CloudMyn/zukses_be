<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\User;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition(): array
    {
        $user = User::factory()->create();
        $seller = Seller::factory()->create();

        return [
            'id_user' => $user->id,
            'session_id' => $this->faker->uuid(),
            'id_seller' => $seller->id,
            'total_items' => $this->faker->numberBetween(1, 10),
            'total_berat' => $this->faker->randomFloat(2, 0.1, 5.0),
            'total_harga' => $this->faker->randomFloat(2, 10000, 1000000),
            'total_diskon' => $this->faker->randomFloat(2, 0, 50000),
            'is_cart_aktif' => $this->faker->boolean(80),
            'kadaluarsa_pada' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}