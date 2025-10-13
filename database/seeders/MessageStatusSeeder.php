<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [];
        
        for ($i = 1; $i <= 1000; $i++) {
            $statuses[] = [
                'id' => $i,
                'id_pesan' => rand(1, 500), // Assuming 500 messages exist
                'id_user' => rand(1, 80), // Assuming 80 users exist
                'status_pesan' => collect(['TERKIRIM', 'DITERIMA', 'DIBACA', 'DITARIK'])->random(),
                'tanggal_status' => now()->subMinutes(rand(0, 10080))->format('Y-m-d H:i:s'), // Up to 1 week ago
                'keterangan_status' => rand(0, 1) ? 'Keterangan status ' . $i : null,
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ];
        }

        DB::table('status_pesan')->insert($statuses);
    }
}