<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserActivity>
 */
class UserActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => $this->faker->numberBetween(1, 100), // Assuming there are 100 users
            'jenis_aktivitas' => $this->faker->randomElement(['LOGIN', 'LOGOUT', 'VIEW_PRODUCT', 'ADD_TO_CART', 'PLACE_ORDER', 'UPDATE_PROFILE', 'DELETE_ACCOUNT']),
            'deskripsi_aktivitas' => $this->faker->sentence,
            'ip_address' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
            'tanggal_aktivitas' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'metadata' => json_encode([
                'device' => $this->faker->randomElement(['Mobile', 'Desktop', 'Tablet']),
                'location' => $this->faker->city,
                'browser' => $this->faker->randomElement(['Chrome', 'Firefox', 'Safari', 'Edge']),
            ]),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}