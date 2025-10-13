<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MessageStatus>
 */
class MessageStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_pesan' => $this->faker->numberBetween(1, 500), // Assuming there are 500 messages
            'id_user' => $this->faker->numberBetween(1, 100), // Assuming there are 100 users
            'status_pesan' => $this->faker->randomElement(['TERKIRIM', 'DITERIMA', 'DIBACA', 'DITARIK']),
            'tanggal_status' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'keterangan_status' => $this->faker->optional(0.5)->sentence,
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}