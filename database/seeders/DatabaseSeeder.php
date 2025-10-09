<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Seller;
use App\Models\Address;
use App\Models\Verification;
use App\Models\Device;
use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\ProductShippingInfo;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingMethod;
use App\Models\PaymentMethod;
use App\Models\ProductReview;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create essential admin user
        User::create([
            'username' => 'admin',
            'email' => 'admin@zukses.com',
            'kata_sandi' => Hash::make('password123'),
            'tipe_user' => 'ADMIN',
            'status' => 'AKTIF',
            'nama_depan' => 'System',
            'nama_belakang' => 'Administrator',
            'nama_lengkap' => 'System Administrator',
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ]);

        // Run other seeders for comprehensive data
        $this->call([
            UserSeeder::class,
            SellerSeeder::class,
            AddressSeeder::class,
            VerificationSeeder::class,
            DeviceSeeder::class,
        ]);
    }
}
