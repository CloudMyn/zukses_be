<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'nomor_telepon' => fake()->unique()->phoneNumber(),
            'kata_sandi' => static::$password ??= Hash::make('password'),
            'tipe_user' => fake()->randomElement(['ADMIN', 'PELANGGAN', 'PEDAGANG']),
            'status' => fake()->randomElement(['AKTIF', 'TIDAK_AKTIF', 'DIBLOKIR', 'SUSPEND']),
            'email_terverifikasi_pada' => fake()->dateTimeBetween('-1 year', 'now'),
            'telepon_terverifikasi_pada' => fake()->dateTimeBetween('-1 year', 'now'),
            'terakhir_login_pada' => fake()->dateTimeBetween('-1 week', 'now'),
            'url_foto_profil' => fake()->imageUrl(),
            'pengaturan' => [
                'tema' => fake()->randomElement(['light', 'dark']),
                'notifikasi' => [
                    'email' => fake()->boolean(),
                    'sms' => fake()->boolean(),
                    'push' => fake()->boolean()
                ],
                'privacy' => [
                    'show_email' => fake()->boolean(),
                    'show_phone' => fake()->boolean()
                ]
            ],
            'nama_depan' => fake()->firstName(),
            'nama_belakang' => fake()->lastName(),
            'nama_lengkap' => fake()->name(),
            'jenis_kelamin' => fake()->randomElement(['LAKI_LAKI', 'PEREMPUAN', 'RAHASIA']),
            'tanggal_lahir' => fake()->date('Y-m-d', '-20 years'),
            'bio' => fake()->paragraph(),
            'url_media_sosial' => [
                'instagram' => fake()->userName(),
                'facebook' => fake()->userName(),
                'twitter' => fake()->userName()
            ],
            'bidang_interests' => [
                fake()->word(),
                fake()->word(),
                fake()->word()
            ],
            'dibuat_pada' => fake()->dateTimeBetween('-2 years', 'now'),
            'diperbarui_pada' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_terverifikasi_pada' => null,
        ]);
    }
}