<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\District;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\District>
 */
class DistrictFactory extends Factory
{
    protected $model = District::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kota_id' => City::factory(),
            'nama' => $this->faker->word() . ' ' . $this->faker->randomElement(['Kecamatan', 'Kec.', 'District']),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}