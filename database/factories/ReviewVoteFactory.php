<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReviewVote>
 */
class ReviewVoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_ulasan_produk' => $this->faker->numberBetween(1, 200), // Assuming there are 200 product reviews
            'id_user' => $this->faker->numberBetween(1, 100), // Assuming there are 100 users
            'jenis_vote' => $this->faker->randomElement(['SUKA', 'TIDAK_SUKA']),
            'tanggal_vote' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}