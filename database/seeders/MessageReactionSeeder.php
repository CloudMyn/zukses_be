<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageReactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reactions = [];
        
        for ($i = 1; $i <= 500; $i++) {
            $reactions[] = [
                'id' => $i,
                'id_pesan' => rand(1, 500), // Assuming 500 messages exist
                'id_user' => rand(1, 80), // Assuming 80 users exist
                'jenis_reaksi' => collect(['LIKE', 'LOVE', 'HAHA', 'WOW', 'SAD', 'ANGRY', 'THUMBS_UP', 'THUMBS_DOWN'])->random(),
                'deskripsi_reaksi' => rand(0, 1) ? 'Deskripsi reaksi ' . $i : null,
                'tanggal_reaksi' => now()->subMinutes(rand(0, 10080))->format('Y-m-d H:i:s'), // Up to 1 week ago
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ];
        }

        DB::table('reaksi_pesan')->insert($reactions);
    }
}