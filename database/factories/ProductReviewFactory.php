<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductReview>
 */
class ProductReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_produk' => $this->faker->numberBetween(1, 100), // Assuming there are 100 products
            'id_user' => $this->faker->numberBetween(1, 100), // Assuming there are 100 users
            'id_order_item' => $this->faker->numberBetween(1, 200), // Assuming there are 200 order items
            'id_varian_produk' => $this->faker->optional(0.7)->numberBetween(1, 50), // Optional product variant
            'rating' => $this->faker->numberBetween(1, 5),
            'judul_ulasan' => $this->faker->sentence,
            'isi_ulasan' => $this->faker->paragraph,
            'is_verified_purchase' => $this->faker->boolean,
            'is_rekomendasi' => $this->faker->boolean,
            'jumlah_suka' => $this->faker->numberBetween(0, 100),
            'jumlah_tidak_suka' => $this->faker->numberBetween(0, 50),
            'tanggal_ulasan' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'status_ulasan' => $this->faker->randomElement(['DRAFT', 'AKTIF', 'TIDAK_AKTIF', 'HAPUS', 'DITOLAK']),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}