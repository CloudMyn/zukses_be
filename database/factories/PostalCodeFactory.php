<?php

namespace Database\Factories;

use App\Models\District;
use App\Models\PostalCode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PostalCode>
 */
class PostalCodeFactory extends Factory
{
    protected $model = PostalCode::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kecamatan_id' => District::factory(),
            'kode' => $this->faker->postcode(),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}