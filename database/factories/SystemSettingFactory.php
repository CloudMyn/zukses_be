<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SystemSetting>
 */
class SystemSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_pengaturan' => $this->faker->randomElement([
                'app_name', 'app_description', 'app_maintenance_mode', 'app_currency',
                'tax_rate', 'shipping_fee', 'minimum_order_amount', 'max_upload_size',
                'default_timezone', 'default_locale', 'smtp_host', 'smtp_port',
                'email_verification_required', 'sms_verification_required'
            ]),
            'nilai_pengaturan' => json_encode($this->faker->randomElement([
                $this->faker->word,
                $this->faker->numberBetween(1, 100),
                $this->faker->boolean,
                [
                    'value' => $this->faker->word,
                    'enabled' => $this->faker->boolean,
                    'config' => ['key' => $this->faker->word]
                ]
            ])),
            'deskripsi_pengaturan' => $this->faker->sentence,
            'kategori_pengaturan' => $this->faker->randomElement([
                'GENERAL', 'PAYMENT', 'SHIPPING', 'EMAIL', 'SECURITY', 'SEO'
            ]),
            'is_aktif' => $this->faker->boolean,
            'urutan_tampilan' => $this->faker->numberBetween(1, 100),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}