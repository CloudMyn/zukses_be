<?php

namespace Database\Seeders;

use App\Models\OrderShipping;
use Illuminate\Database\Seeder;

class OrderShippingSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample order shipping records for testing
        OrderShipping::factory(20)->create();
    }
}