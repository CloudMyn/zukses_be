<?php

namespace Database\Factories;

use App\Models\UserSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserSession>
 */
class UserSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->uuid(),
            'id_user' => User::factory(),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'payload' => fake()->text(1000),
            'aktivitas_terakhir' => fake()->unixTime(),
            'dibuat_pada' => fake()->dateTime(),
        ];
    }
}