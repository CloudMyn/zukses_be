<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentTransaction>
 */
class PaymentTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_pesanan' => $this->faker->numberBetween(1, 50), // Assuming there are 50 orders
            'id_metode_pembayaran' => $this->faker->numberBetween(1, 4), // Assuming there are 4 payment methods
            'reference_id' => $this->faker->uuid,
            'jumlah_pembayaran' => $this->faker->randomFloat(2, 10000, 10000000),
            'status_transaksi' => $this->faker->randomElement(['MENUNGGU', 'BERHASIL', 'GAGAL', 'KADALUARSA']),
            'channel_pembayaran' => $this->faker->optional(0.3)->word,
            'va_number' => $this->faker->optional(0.2)->numerify('###########'),
            'qr_code' => $this->faker->optional(0.1)->url,
            'deep_link' => $this->faker->optional(0.1)->url,
            'tanggal_kadaluarsa' => $this->faker->optional(0.5)->dateTimeBetween('now', '+7 days'),
            'tanggal_bayar' => $this->faker->optional(0.7)->dateTimeBetween('-1 month', 'now'),
            'response_gateway' => json_encode([
                'payment_gateway' => $this->faker->randomElement(['Midtrans', 'Xendit', 'Doku']),
                'payment_type' => $this->faker->randomElement(['credit_card', 'bank_transfer', 'ewallet']),
                'payment_code' => $this->faker->bothify('CODE-####'),
            ]),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}