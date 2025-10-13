<?php

namespace Database\Factories;

use App\Models\ChatMessage;
use App\Models\ChatProductReference;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChatProductReference>
 */
class ChatProductReferenceFactory extends Factory
{
    protected $model = ChatProductReference::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pesan_id' => ChatMessage::factory(),
            'product_id' => $this->faker->optional()->randomElement(Product::pluck('id')->toArray()),
            'marketplace_product_id' => $this->faker->optional()->bothify('PROD-????-####'),
            'snapshot' => [
                'nama_produk' => $this->faker->productName(),
                'harga' => $this->faker->randomElement([10000, 15000, 20000, 25000, 30000, 50000]),
                'gambar_produk' => $this->faker->imageUrl(),
                'deskripsi_produk' => $this->faker->sentence(),
            ],
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}