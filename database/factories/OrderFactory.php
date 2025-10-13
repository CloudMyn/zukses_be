<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $user = User::factory()->create();
        $seller = Seller::factory()->create();

        return [
            'nomor_pesanan' => 'ORD-' . $this->faker->unique()->numerify('########'),
            'id_customer' => $user->id,
            'id_alamat_pengiriman' => null, // Will be set when address is created
            'status_pesanan' => $this->faker->randomElement(['MENUNGGU_PEMBAYARAN', 'DIBAYAR', 'DIKEMAS', 'DIKIRIM', 'SELESAI', 'BATAL', 'DIKEMBALIKAN']),
            'status_pembayaran' => $this->faker->randomElement(['BELUM_DIBAYAR', 'SUDAH_DIBAYAR', 'KADALUARSA', 'DIBATALKAN']),
            'total_items' => $this->faker->numberBetween(1, 10),
            'total_berat' => $this->faker->randomFloat(2, 0.1, 20.0),
            'subtotal_produk' => $this->faker->randomFloat(2, 50000, 5000000),
            'total_diskon_produk' => $this->faker->randomFloat(2, 0, 100000),
            'total_ongkir' => $this->faker->randomFloat(2, 5000, 100000),
            'total_biaya_layanan' => $this->faker->randomFloat(2, 0, 5000),
            'total_pajak' => $this->faker->randomFloat(2, 0, 50000),
            'total_pembayaran' => $this->faker->randomFloat(2, 100000, 6000000),
            'metode_pembayaran' => $this->faker->randomElement(['TRANSFER_BANK', 'GOPAY', 'OVO', 'DANA', 'CASH_ON_DELIVERY']),
            'bank_pembayaran' => $this->faker->optional()->randomElement(['BCA', 'BNI', 'BRI', 'MANDIRI', 'BSI']),
            'va_number' => $this->faker->optional()->numerify('################'),
            'deadline_pembayaran' => $this->faker->optional()->dateTimeBetween('+1 hour', '+1 day'),
            'tanggal_dibayar' => $this->faker->optional()->dateTimeBetween('-1 week', 'now'),
            'no_resi' => $this->faker->optional()->bothify('???#########'),
            'catatan_pesanan' => $this->faker->optional()->sentence(),
            'tanggal_pengiriman' => $this->faker->optional()->dateTimeBetween('-1 week', 'now'),
            'tanggal_selesai' => $this->faker->optional()->dateTimeBetween('-1 week', 'now'),
            'tanggal_dibatalkan' => $this->faker->optional()->dateTimeBetween('-1 week', 'now'),
            'alasan_pembatalan' => $this->faker->optional()->sentence(),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}