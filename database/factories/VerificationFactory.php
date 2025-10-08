<?php

namespace Database\Factories;

use App\Models\Verification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Verification>
 */
class VerificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'jenis_verifikasi' => fake()->randomElement(['EMAIL', 'TELEPON', 'KTP', 'NPWP']),
            'nilai_verifikasi' => fake()->email(),
            'kode_verifikasi' => fake()->numerify('######'),
            'kedaluwarsa_pada' => fake()->dateTimeBetween('now', '+1 hour'),
            'telah_digunakan' => fake()->boolean(),
            'jumlah_coba' => fake()->numberBetween(0, 3),
            'dibuat_pada' => fake()->dateTime(),
            'diperbarui_pada' => fake()->dateTime(),
        ];
    }
}