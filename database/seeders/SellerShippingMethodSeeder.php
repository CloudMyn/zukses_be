<?php

namespace Database\Seeders;

use App\Models\SellerShippingMethod;
use Illuminate\Database\Seeder;

class SellerShippingMethodSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample seller shipping methods for testing
        SellerShippingMethod::factory(20)->create();
    }
}