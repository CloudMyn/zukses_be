<?php

namespace Database\Seeders;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Seeder;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a user to assign as seller
        $user = User::where('tipe_user', 'PEDAGANG')->first();
        
        if ($user) {
            Seller::factory()->create([
                'id_user' => $user->id,
                'nama_toko' => 'Toko Ahmad',
                'slug_toko' => 'toko-ahmad',
                'deskripsi_toko' => 'Toko yang menjual berbagai kebutuhan pokok',
                'nomor_ktp' => '1234567890123456',
                'nomor_npwp' => '123456789012',
                'status_verifikasi' => 'TERVERIFIKASI',
            ]);
        }

        Seller::factory(5)->create();
    }
}