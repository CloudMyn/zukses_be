<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SearchHistory>
 */
class SearchHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => $this->faker->optional(0.7)->numberBetween(1, 100), // Most searches are by logged in users
            'kata_kunci_pencarian' => $this->faker->word,
            'jumlah_hasil' => $this->faker->numberBetween(0, 100),
            'tanggal_pencarian' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'ip_address' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
            'sumber_pencarian' => $this->faker->randomElement(['WEB', 'MOBILE_APP', 'MOBILE_WEB']),
            'metadata' => json_encode([
                'filters' => [
                    'category' => $this->faker->optional(0.3)->word,
                    'min_price' => $this->faker->optional(0.2)->randomNumber(4),
                    'max_price' => $this->faker->optional(0.2)->randomNumber(5),
                ],
            ]),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}