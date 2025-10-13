<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChatConversation>
 */
class ChatConversationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_obrolan' => $this->faker->sentence(3),
            'deskripsi_obrolan' => $this->faker->optional(0.7)->sentence,
            'jenis_obrolan' => $this->faker->randomElement(['PRIVAT', 'GROUP', 'ORDER', 'SUPPORT']),
            'is_group' => $this->faker->boolean,
            'is_active' => $this->faker->boolean,
            'jumlah_partisipan' => $this->faker->numberBetween(2, 10),
            'id_pembuat' => $this->faker->numberBetween(1, 100), // Assuming there are 100 users
            'tanggal_pembuatan' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}