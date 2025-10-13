<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserNotification>
 */
class UserNotificationFactory extends Factory
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
            'judul_notifikasi' => $this->faker->sentence,
            'isi_notifikasi' => $this->faker->paragraph,
            'jenis_notifikasi' => $this->faker->randomElement(['ORDER', 'PROMO', 'SYSTEM', 'PAYMENT', 'PRODUCT']),
            'status_pembacaan' => $this->faker->randomElement(['DIBACA', 'BELUM_DIBACA']),
            'tanggal_notifikasi' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'tanggal_pembacaan' => $this->faker->optional(0.7)->dateTimeBetween('-1 week', 'now'),
            'metadata' => json_encode([
                'action_url' => $this->faker->url,
                'priority' => $this->faker->randomElement(['LOW', 'MEDIUM', 'HIGH']),
            ]),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}