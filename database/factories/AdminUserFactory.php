<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdminUser>
 */
class AdminUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => $this->faker->numberBetween(1, 20), // Assuming there are 20 user records with admin type
            'nip' => 'NIP-' . $this->faker->unique()->numerify('##########'),
            'nama_lengkap' => $this->faker->name,
            'nomor_telepon' => $this->faker->phoneNumber,
            'departemen' => $this->faker->randomElement(['IT', 'HR', 'Finance', 'Operations', 'Marketing']),
            'jabatan' => $this->faker->randomElement(['Staff', 'Supervisor', 'Manager', 'Director']),
            'tanggal_mulai_bekerja' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'is_active' => $this->faker->boolean,
            'catatan' => $this->faker->optional(0.3)->sentence,
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}