<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentLog>
 */
class PaymentLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_transaksi_pembayaran' => $this->faker->numberBetween(1, 50), // Assuming there are 50 payment transactions
            'id_user' => $this->faker->numberBetween(1, 100), // Assuming there are 100 users
            'aksi_log' => $this->faker->word,
            'deskripsi_log' => $this->faker->sentence,
            'data_sebelumnya' => json_encode(['old_value' => $this->faker->word]),
            'data_perubahan' => json_encode(['new_value' => $this->faker->word]),
            'ip_address' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
            'dibuat_pada' => now(),
        ];
    }
}