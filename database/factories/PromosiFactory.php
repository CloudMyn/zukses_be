<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Promosi;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromosiFactory extends Factory
{
    protected $model = Promosi::class;

    public function definition(): array
    {
        return [
            'kode_promosi' => $this->faker->unique()->bothify('PROMO-#####'),
            'nama_promosi' => $this->faker->sentence(3),
            'deskripsi' => $this->faker->paragraph,
            'jenis_promosi' => $this->faker->randomElement(['KODE_PROMOSI', 'OTOMATIS', 'MEMBER', 'KELOMPOK_PRODUK']),
            'tipe_diskon' => $this->faker->randomElement(['PERSEN', 'NOMINAL', 'BONUS_PRODUK']),
            'nilai_diskon' => $this->faker->randomElement([10, 15, 20, 25, 30]),
            'jumlah_maksimum_penggunaan' => $this->faker->numberBetween(100, 1000),
            'jumlah_penggunaan_saat_ini' => 0,
            'jumlah_maksimum_penggunaan_per_pengguna' => $this->faker->numberBetween(1, 5),
            'tanggal_mulai' => now()->subDays(rand(1, 10)),
            'tanggal_berakhir' => now()->addDays(rand(15, 60)),
            'minimum_pembelian' => $this->faker->randomElement([50000, 100000, 200000, 500000]),
            'id_kategori_produk' => null,
            'dapat_digabungkan' => $this->faker->boolean,
            'status_aktif' => $this->faker->boolean,
            'id_pembuat' => User::factory(),
            'id_pembaharu_terakhir' => User::factory(),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}