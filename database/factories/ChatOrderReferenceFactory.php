<?php

namespace Database\Factories;

use App\Models\ChatMessage;
use App\Models\ChatOrderReference;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChatOrderReference>
 */
class ChatOrderReferenceFactory extends Factory
{
    protected $model = ChatOrderReference::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pesan_id' => ChatMessage::factory(),
            'order_id' => $this->faker->optional()->randomElement(Order::pluck('id')->toArray()),
            'marketplace_order_id' => $this->faker->optional()->bothify('ORD-????-####'),
            'snapshot' => [
                'nomor_pesanan' => $this->faker->bothify('ORD-#####'),
                'total_pembayaran' => $this->faker->randomElement([100000, 150000, 200000, 250000, 300000]),
                'status_pesanan' => $this->faker->randomElement(['KERANJANG', 'MENUNGGU_PEMBAYARAN', 'TERBAYAR', 'DIKIRIM', 'SELESAI']),
                'item_count' => $this->faker->numberBetween(1, 5),
            ],
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}