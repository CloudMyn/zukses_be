<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MessageEdit>
 */
class MessageEditFactory extends Factory
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
            'isi_pesan_lama' => $this->faker->sentence,
            'isi_pesan_baru' => $this->faker->sentence,
            'tanggal_edit' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'alasan_edit' => $this->faker->optional(0.5)->sentence,
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}