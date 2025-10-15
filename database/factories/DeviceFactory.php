<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Device>
 */
class DeviceFactory extends Factory
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
            'device_id' => fake()->uuid(),
            'device_type' => fake()->randomElement(['MOBILE', 'TABLET', 'DESKTOP', 'TV']),
            'device_name' => fake()->word() . ' ' . fake()->randomElement(['Phone', 'Tablet', 'Computer', 'TV']),
            'operating_system' => fake()->randomElement(['Android', 'iOS', 'Windows', 'macOS', 'Linux']),
            'app_version' => fake()->semver(),
            'push_token' => fake()->sha256,
            'adalah_device_terpercaya' => fake()->boolean(),
            'terakhir_aktif_pada' => fake()->dateTime(),
            'dibuat_pada' => fake()->dateTime(),
            'diperbarui_pada' => fake()->dateTime(),
            // Virtual attributes for testing
            'is_trusted' => fake()->boolean(),
        ];
    }
}