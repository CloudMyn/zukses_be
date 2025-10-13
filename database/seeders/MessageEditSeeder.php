<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageEditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $edits = [];
        
        for ($i = 1; $i <= 200; $i++) {
            $edits[] = [
                'id' => $i,
                'id_pesan' => rand(1, 500), // Assuming 500 messages exist
                'id_user' => rand(1, 80), // Assuming 80 users exist
                'isi_pesan_lama' => 'Pesan lama ' . $i,
                'isi_pesan_baru' => 'Pesan baru ' . $i,
                'tanggal_edit' => now()->subMinutes(rand(0, 10080))->format('Y-m-d H:i:s'), // Up to 1 week ago
                'alasan_edit' => rand(0, 1) ? 'Alasan edit ' . $i : null,
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ];
        }

        DB::table('edit_pesan')->insert($edits);
    }
}