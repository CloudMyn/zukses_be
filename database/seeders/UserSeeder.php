<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@zukses.com',
            'nomor_telepon' => '+6281234567890',
            'kata_sandi' => bcrypt('password123'),
            'tipe_user' => 'ADMIN',
            'status' => 'AKTIF',
            'nama_depan' => 'System',
            'nama_belakang' => 'Administrator',
            'nama_lengkap' => 'System Administrator',
        ]);

        // Create seller user
        User::factory()->create([
            'username' => 'seller1',
            'email' => 'seller1@zukses.com',
            'nomor_telepon' => '+6281234567891',
            'kata_sandi' => bcrypt('password123'),
            'tipe_user' => 'PEDAGANG',
            'status' => 'AKTIF',
            'nama_depan' => 'Ahmad',
            'nama_belakang' => 'Sulistyo',
            'nama_lengkap' => 'Ahmad Sulistyo',
        ]);

        // Create regular customer user
        User::factory()->create([
            'username' => 'customer1',
            'email' => 'customer1@zukses.com',
            'nomor_telepon' => '+6281234567892',
            'kata_sandi' => bcrypt('password123'),
            'tipe_user' => 'PELANGGAN',
            'status' => 'AKTIF',
            'nama_depan' => 'Budi',
            'nama_belakang' => 'Santoso',
            'nama_lengkap' => 'Budi Santoso',
        ]);

        // Create additional users
        User::factory(10)->create();
    }
}