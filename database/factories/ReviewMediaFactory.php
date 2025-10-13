<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReviewMedia>
 */
class ReviewMediaFactory extends Factory
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
            'url_media' => $this->faker->imageUrl(600, 400, 'review'),
            'jenis_media' => $this->faker->randomElement(['IMAGE', 'VIDEO']),
            'deskripsi_media' => $this->faker->optional(0.7)->sentence,
            'urutan_tampilan' => $this->faker->numberBetween(1, 10),
            'is_verified' => $this->faker->boolean,
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}