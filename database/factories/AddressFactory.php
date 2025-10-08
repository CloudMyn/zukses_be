<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
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
            'label_alamat' => fake()->randomElement(['Rumah', 'Kantor', 'Gudang', 'Toko']),
            'nama_penerima' => fake()->name(),
            'nomor_telepon_penerima' => fake()->phoneNumber(),
            'alamat_lengkap' => fake()->address(),
            'id_provinsi' => fake()->numberBetween(1, 34),
            'nama_provinsi' => fake()->state(),
            'id_kabupaten' => fake()->numberBetween(1, 500),
            'nama_kabupaten' => fake()->city(),
            'id_kecamatan' => fake()->numberBetween(1, 2000),
            'nama_kecamatan' => fake()->word() . ' Kecamatan',
            'id_kelurahan' => fake()->numberBetween(1, 10000),
            'nama_kelurahan' => fake()->word() . ' Kelurahan',
            'kode_pos' => fake()->postcode(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'adalah_alamat_utama' => fake()->boolean(),
            'tipe_alamat' => fake()->randomElement(['RUMAH', 'KANTOR', 'GUDANG', 'LAINNYA']),
            'dibuat_pada' => fake()->dateTime(),
            'diperbarui_pada' => fake()->dateTime(),
        ];
    }
}