<?php

namespace Database\Factories;

use App\Models\ShippingMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingMethodFactory extends Factory
{
    protected $model = ShippingMethod::class;

    public function definition(): array
    {
        return [
            'nama_metode' => $this->faker->company(),
            'kode_metode' => strtoupper($this->faker->lexify('???_???')),
            'deskripsi_metode' => $this->faker->sentence(),
            'gambar_metode' => $this->faker->optional()->imageUrl(),
            'is_aktif' => $this->faker->boolean(80),
            'urutan_tampilan' => $this->faker->numberBetween(1, 100),
            'tipe_pengiriman' => $this->faker->randomElement(['STANDARD', 'EXPRESS', 'SAME_DAY', 'NEXT_DAY']),
            'min_berat' => $this->faker->randomFloat(2, 0, 1),
            'max_berat' => $this->faker->randomFloat(2, 1, 100),
            'min_nilai' => $this->faker->randomFloat(2, 0, 10000),
            'max_nilai' => $this->faker->randomFloat(2, 10000, 1000000),
            'biaya_minimum' => $this->faker->randomFloat(2, 5000, 50000),
            'biaya_maximum' => $this->faker->randomFloat(2, 50000, 200000),
            'konfigurasi_metode' => json_encode([
                'estimasi_pengiriman' => $this->faker->randomElement(['1-2 hari', '2-3 hari', '3-5 hari']),
                'area_cakupan' => $this->faker->randomElement(['LOKAL', 'NASIONAL', 'INTERNASIONAL']),
            ]),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}