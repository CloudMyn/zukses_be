<?php

namespace Database\Factories;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seller>
 */
class SellerFactory extends Factory
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
            'nama_toko' => fake()->company(),
            'slug_toko' => fake()->slug(),
            'deskripsi_toko' => fake()->paragraph(),
            'logo_toko' => fake()->imageUrl(),
            'banner_toko' => fake()->imageUrl(),
            'nomor_ktp' => fake()->numerify('##########'),
            'foto_ktp' => fake()->imageUrl(),
            'nomor_npwp' => fake()->numerify('############'),
            'foto_npwp' => fake()->imageUrl(),
            'jenis_usaha' => fake()->randomElement(['INDIVIDU', 'PERUSAHAAN']),
            'status_verifikasi' => fake()->randomElement(['MENUNGGU', 'TERVERIFIKASI', 'DITOLAK', 'PERLU_DIREVISI']),
            'tanggal_verifikasi' => fake()->date(),
            'id_verifikator' => null,
            'catatan_verifikasi' => fake()->sentence(),
            'rating_toko' => fake()->randomFloat(2, 1, 5),
            'total_penjualan' => fake()->numberBetween(0, 1000),
            'dibuat_pada' => fake()->dateTime(),
            'diperbarui_pada' => fake()->dateTime(),
        ];
    }
}