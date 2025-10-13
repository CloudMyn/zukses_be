<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentMethod>
 */
class PaymentMethodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_pembayaran' => $this->faker->randomElement(['Bank Transfer', 'Credit Card', 'Debit Card', 'E-Wallet', 'COD']),
            'tipe_pembayaran' => $this->faker->randomElement(['TRANSFER_BANK', 'E_WALLET', 'VIRTUAL_ACCOUNT', 'CREDIT_CARD', 'COD', 'QRIS']),
            'provider_pembayaran' => $this->faker->randomElement(['BCA', 'GOPAY', 'OVO', 'DANA', 'BNI', 'Mandiri']),
            'logo_pembayaran' => $this->faker->imageUrl(64, 64, 'payment'),
            'deskripsi_pembayaran' => $this->faker->sentence,
            'biaya_admin_percent' => $this->faker->randomFloat(2, 0, 5),
            'biaya_admin_fixed' => $this->faker->randomFloat(2, 0, 10000),
            'minimum_pembayaran' => $this->faker->numberBetween(10000, 1000000),
            'maksimum_pembayaran' => $this->faker->numberBetween(10000000, 100000000),
            'is_aktif' => $this->faker->boolean,
            'urutan_tampilan' => $this->faker->numberBetween(1, 100),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}