<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariantPrice;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create();
        $variantPrice = ProductVariantPrice::factory()->create();

        return [
            'id_pesanan' => $order->id,
            'id_produk' => $product->id,
            'id_harga_varian' => $variantPrice->id,
            'kuantitas' => $this->faker->numberBetween(1, 5),
            'harga_satuan' => $this->faker->randomFloat(2, 10000, 1000000),
            'harga_total' => $this->faker->randomFloat(2, 50000, 2000000),
            'diskon_item' => $this->faker->randomFloat(2, 0, 50000),
            'pajak_item' => $this->faker->randomFloat(2, 0, 50000),
            'biaya_layanan_item' => $this->faker->randomFloat(2, 0, 10000),
            'catatan_item' => $this->faker->optional()->sentence(),
            'gambar_produk' => $this->faker->optional()->imageUrl(),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}