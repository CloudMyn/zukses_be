<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MessageReaction>
 */
class MessageReactionFactory extends Factory
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
            'jenis_reaksi' => $this->faker->randomElement(['LIKE', 'LOVE', 'HAHA', 'WOW', 'SAD', 'ANGRY', 'THUMBS_UP', 'THUMBS_DOWN']),
            'deskripsi_reaksi' => $this->faker->optional(0.3)->sentence,
            'tanggal_reaksi' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}