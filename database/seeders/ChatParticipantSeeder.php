<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChatParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $participants = [];
        
        for ($i = 1; $i <= 300; $i++) {
            $participants[] = [
                'id' => $i,
                'id_obrolan' => rand(1, 50), // Assuming 50 conversations exist
                'id_user' => rand(1, 80), // Assuming 80 users exist
                'status_partisipan' => collect(['ACTIVE', 'LEFT', 'BANNED', 'INVITED'])->random(),
                'is_admin' => rand(0, 1) > 0.9 ? 1 : 0, // ~10% admin
                'is_muted' => rand(0, 1),
                'tanggal_join' => now()->subDays(rand(0, 30))->format('Y-m-d H:i:s'),
                'tanggal_keluar' => rand(0, 1) ? now()->subDays(rand(0, 29))->format('Y-m-d H:i:s') : null,
                'catatan_partisipan' => rand(0, 1) ? 'Catatan partisipan #' . $i : null,
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ];
        }

        DB::table('partisipan_obrolan')->insert($participants);
    }
}