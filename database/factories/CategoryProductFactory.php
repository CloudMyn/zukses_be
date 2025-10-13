<?php

namespace Database\Factories;

use App\Models\CategoryProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CategoryProduct>
 */
class CategoryProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CategoryProduct::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categoryNames = [
            'Elektronik', 'Fashion Pria', 'Fashion Wanita', 'Kesehatan', 'Kecantikan',
            'Olahraga', 'Makanan & Minuman', 'Mainan & Hobi', 'Rumah Tangga', 'Otomotif',
            'Komputer & Laptop', 'Handphone & Aksesoris', 'Kamera & Fotografi', 'Gaming',
            'Buku & Alat Tulis', 'Musik & Film', 'Pertukangan', 'Pertanian', 'Peternakan',
            'Ibu & Anak', 'Muslim Fashion', 'Travel & Leisure', 'Art & Craft'
        ];

        $name = fake()->unique()->randomElement($categoryNames) . ' ' . fake()->randomElement(['Premium', 'Basic', 'Professional', 'Exclusive', 'Standard']);

        return [
            'nama_kategori' => $name,
            'slug_kategori' => str_slug($name),
            'deskripsi_kategori' => fake()->paragraph(3),
            'gambar_kategori' => 'categories/' . fake()->uuid() . '.jpg',
            'icon_kategori' => 'categories/icons/' . fake()->uuid() . '.svg',
            'id_kategori_induk' => null,
            'level_kategori' => 0,
            'urutan_tampilan' => fake()->numberBetween(1, 100),
            'is_kategori_aktif' => true,
            'is_kategori_featured' => fake()->boolean(30), // 30% chance to be featured
            'meta_title' => $name . ' - Beli Sekarang',
            'meta_description' => fake()->sentence(15),
            'dibuat_pada' => fake()->dateTimeBetween('-1 year', 'now'),
            'diperbarui_pada' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Indicate that the category is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_kategori_aktif' => false,
        ]);
    }

    /**
     * Indicate that the category is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_kategori_featured' => true,
        ]);
    }

    /**
     * Indicate that the category is a root category.
     */
    public function root(): static
    {
        return $this->state(fn (array $attributes) => [
            'id_kategori_induk' => null,
            'level_kategori' => 0,
        ]);
    }

    /**
     * Indicate that the category is a sub category.
     */
    public function subCategory(CategoryProduct $parent = null): static
    {
        return $this->state(fn (array $attributes) => [
            'id_kategori_induk' => $parent?->id ?? CategoryProduct::factory()->root(),
            'level_kategori' => 1,
        ]);
    }

    /**
     * Indicate that the category is a sub sub category.
     */
    public function subSubCategory(CategoryProduct $parent = null): static
    {
        return $this->state(fn (array $attributes) => [
            'id_kategori_induk' => $parent?->id ?? CategoryProduct::factory()->subCategory(),
            'level_kategori' => 2,
        ]);
    }

    /**
     * Create a category with specific level.
     */
    public function level(int $level): static
    {
        return $this->state(fn (array $attributes) => [
            'level_kategori' => $level,
            'id_kategori_induk' => $level > 0 ?
                CategoryProduct::factory()->level($level - 1) :
                null,
        ]);
    }

    /**
     * Create an electronics category tree.
     */
    public function electronics(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_kategori' => 'Elektronik',
            'slug_kategori' => 'elektronik',
            'deskripsi_kategori' => 'Semua kebutuhan elektronik dan gadget terbaru dengan harga terbaik',
            'level_kategori' => 0,
            'id_kategori_induk' => null,
            'is_kategori_featured' => true,
        ]);
    }

    /**
     * Create a fashion category tree.
     */
    public function fashion(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_kategori' => 'Fashion',
            'slug_kategori' => 'fashion',
            'deskripsi_kategori' => 'Trend fashion terkini untuk pria dan wanita dengan berbagai pilihan gaya',
            'level_kategori' => 0,
            'id_kategori_induk' => null,
            'is_kategori_featured' => true,
        ]);
    }

    /**
     * Create a food category tree.
     */
    public function food(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_kategori' => 'Makanan & Minuman',
            'slug_kategori' => 'makanan-minuman',
            'deskripsi_kategori' => 'Makanan, minuman, dan bahan makanan segar dengan kualitas terjamin',
            'level_kategori' => 0,
            'id_kategori_induk' => null,
            'is_kategori_featured' => true,
        ]);
    }

    /**
     * Create a home & living category tree.
     */
    public function home(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_kategori' => 'Rumah Tangga',
            'slug_kategori' => 'rumah-tangga',
            'deskripsi_kategori' => 'Peralatan dan perlengkapan rumah tangga untuk kebutuhan sehari-hari',
            'level_kategori' => 0,
            'id_kategori_induk' => null,
            'is_kategori_featured' => false,
        ]);
    }
}