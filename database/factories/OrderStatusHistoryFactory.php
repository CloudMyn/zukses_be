<?php

namespace Database\Factories;

use App\Models\OrderStatusHistory;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderStatusHistoryFactory extends Factory
{
    protected $model = OrderStatusHistory::class;

    public function definition(): array
    {
        $order = Order::factory()->create();
        $user = User::factory()->create();

        return [
            'id_pesanan' => $order->id,
            'status_sebelumnya' => $this->faker->randomElement(['MENUNGGU_PEMBAYARAN', 'DIBAYAR', 'DIKEMAS']),
            'status_baru' => $this->faker->randomElement(['DIBAYAR', 'DIKEMAS', 'DIKIRIM', 'SELESAI']),
            'alasan_perubahan' => $this->faker->sentence(),
            'catatan_perubahan' => $this->faker->optional()->paragraph(),
            'diubah_oleh_id' => $user->id,
            'diubah_oleh_tipe' => $this->faker->randomElement(['CUSTOMER', 'SELLER', 'ADMIN', 'SYSTEM']),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}