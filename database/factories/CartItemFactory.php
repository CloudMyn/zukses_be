<?php

namespace Database\Factories;

use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariantPrice;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    protected $model = CartItem::class;

    public function definition(): array
    {
        $cart = Cart::factory()->create();
        $product = Product::factory()->create();
        $variantPrice = ProductVariantPrice::factory()->create();

        return [
            'id_cart' => $cart->id,
            'id_produk' => $product->id,
            'id_harga_varian' => $variantPrice->id,
            'kuantitas' => $this->faker->numberBetween(1, 5),
            'harga_satuan' => $this->faker->randomFloat(2, 10000, 1000000),
            'harga_total' => $this->faker->randomFloat(2, 50000, 2000000),
            'diskon_item' => $this->faker->randomFloat(2, 0, 50000),
            'catatan_item' => $this->faker->optional()->sentence(),
            'gambar_produk' => $this->faker->optional()->imageUrl(),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}