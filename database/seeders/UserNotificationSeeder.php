<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $notifications = [];
        
        for ($i = 1; $i <= 500; $i++) {
            $notifications[] = [
                'id' => $i,
                'id_user' => rand(1, 80), // Assuming 80 users exist
                'judul_notifikasi' => 'Notifikasi #' . $i,
                'isi_notifikasi' => 'Ini adalah isi dari notifikasi #' . $i,
                'jenis_notifikasi' => collect(['ORDER', 'PROMO', 'SYSTEM', 'PAYMENT', 'PRODUCT'])->random(),
                'status_pembacaan' => collect(['DIBACA', 'BELUM_DIBACA'])->random(),
                'tanggal_notifikasi' => now()->subDays(rand(0, 7))->format('Y-m-d H:i:s'),
                'tanggal_pembacaan' => rand(0, 1) ? now()->subDays(rand(0, 6))->format('Y-m-d H:i:s') : null,
                'metadata' => json_encode([
                    'action_url' => '/notification/' . $i,
                    'priority' => collect(['LOW', 'MEDIUM', 'HIGH'])->random(),
                ]),
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ];
        }

        DB::table('notifikasi_user')->insert($notifications);
    }
}