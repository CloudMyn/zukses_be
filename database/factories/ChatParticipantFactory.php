<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChatParticipant>
 */
class ChatParticipantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_obrolan' => $this->faker->numberBetween(1, 50), // Assuming there are 50 conversations
            'id_user' => $this->faker->numberBetween(1, 100), // Assuming there are 100 users
            'status_partisipan' => $this->faker->randomElement(['ACTIVE', 'LEFT', 'BANNED', 'INVITED']),
            'is_admin' => $this->faker->boolean(0.1), // 10% chance of being admin
            'is_muted' => $this->faker->boolean,
            'tanggal_join' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'tanggal_keluar' => $this->faker->optional(0.3)->dateTimeBetween('-1 month', 'now'),
            'catatan_partisipan' => $this->faker->optional(0.5)->sentence,
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}