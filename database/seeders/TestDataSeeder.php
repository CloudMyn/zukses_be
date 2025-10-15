<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Seller;
use App\Models\CategoryProduct;
use App\Models\Product;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test categories
        $categories = CategoryProduct::factory()->count(5)->create();

        // Create test sellers with products
        User::where('tipe_user', 'PEDAGANG')->get()->each(function ($user) use ($categories) {
            $seller = Seller::factory()->create(['id_user' => $user->id]);

            // Create products for each seller
            Product::factory()->count(10)->create([
                'id_seller' => $seller->id,
                'id_kategori' => $categories->random()->id
            ]);
        });

        // Create test addresses
        User::all()->each(function ($user) {
            \App\Models\Address::factory()->count(2)->create(['id_user' => $user->id]);
        });
    }
}
