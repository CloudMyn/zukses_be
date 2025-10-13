<?php

namespace Database\Factories;

use App\Models\OrderShipping;
use App\Models\Order;
use App\Models\ShippingMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderShippingFactory extends Factory
{
    protected $model = OrderShipping::class;

    public function definition(): array
    {
        $order = Order::factory()->create();
        $shippingMethod = ShippingMethod::factory()->create();

        return [
            'id_pesanan' => $order->id,
            'id_metode_pengiriman' => $shippingMethod->id,
            'nama_metode_pengiriman' => $this->faker->company(),
            'estimasi_pengiriman' => $this->faker->randomElement(['1-2 hari', '2-3 hari', '3-5 hari', '1 minggu']),
            'biaya_pengiriman' => $this->faker->randomFloat(2, 5000, 50000),
            'kode_pengiriman' => strtoupper($this->faker->lexify('???_????_######')),
            'kurir_pengiriman' => $this->faker->name(),
            'status_pengiriman' => $this->faker->randomElement(['MENUNGGU_PENGIRIMAN', 'DIKEMAS', 'DIKIRIM', 'DITERIMA', 'DIBATALKAN', 'DIKEMBALIKAN']),
            'tanggal_pengiriman' => $this->faker->optional()->dateTimeBetween('-1 week', 'now'),
            'tanggal_diterima' => $this->faker->optional()->dateTimeBetween('-1 week', 'now'),
            'bukti_penerimaan' => $this->faker->optional()->imageUrl(),
            'catatan_pengiriman' => $this->faker->optional()->sentence(),
            'koordinat_asal' => json_encode([
                'latitude' => $this->faker->latitude(),
                'longitude' => $this->faker->longitude(),
            ]),
            'koordinat_tujuan' => json_encode([
                'latitude' => $this->faker->latitude(),
                'longitude' => $this->faker->longitude(),
            ]),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}