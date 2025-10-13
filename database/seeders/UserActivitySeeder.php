<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activities = [];
        
        for ($i = 1; $i <= 1000; $i++) {
            $activities[] = [
                'id' => $i,
                'id_user' => rand(1, 80), // Assuming 80 users exist
                'jenis_aktivitas' => collect(['LOGIN', 'LOGOUT', 'VIEW_PRODUCT', 'ADD_TO_CART', 'PLACE_ORDER', 'UPDATE_PROFILE', 'DELETE_ACCOUNT'])->random(),
                'deskripsi_aktivitas' => 'Aktivitas pengguna #' . $i,
                'ip_address' => $this->faker->ipv4,
                'user_agent' => $this->faker->userAgent,
                'tanggal_aktivitas' => now()->subDays(rand(0, 30))->format('Y-m-d H:i:s'),
                'metadata' => json_encode([
                    'device' => collect(['Mobile', 'Desktop', 'Tablet'])->random(),
                    'location' => $this->faker->city,
                    'browser' => collect(['Chrome', 'Firefox', 'Safari', 'Edge'])->random(),
                ]),
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ];
        }

        DB::table('aktivitas_user')->insert($activities);
    }
}