<?php

namespace Database\Factories;

use App\Models\Promosi;
use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class RiwayatPenggunaanPromosiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_promosi' => Promosi::factory(),
            'id_pengguna' => User::factory(),
            'id_pesanan' => $this->faker->boolean(50) ? Order::factory() : null,  // 50% chance of having an order
            'tanggal_penggunaan' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'jumlah_diskon_diterapkan' => $this->faker->randomElement([10000, 15000, 20000, 25000, 30000]),
            'dibuat_pada' => now(),
        ];
    }
}