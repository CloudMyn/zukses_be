<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChatConversationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $conversations = [];
        
        for ($i = 1; $i <= 100; $i++) {
            $conversations[] = [
                'id' => $i,
                'nama_obrolan' => 'Obrolan ' . $i,
                'deskripsi_obrolan' => 'Deskripsi obrolan #' . $i,
                'jenis_obrolan' => collect(['PRIVAT', 'GROUP', 'ORDER', 'SUPPORT'])->random(),
                'is_group' => rand(0, 1),
                'is_active' => rand(0, 1),
                'jumlah_partisipan' => rand(2, 10),
                'id_pembuat' => rand(1, 50), // Assuming 50 users exist
                'tanggal_pembuatan' => now()->subDays(rand(0, 30))->format('Y-m-d H:i:s'),
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ];
        }

        DB::table('obrolan')->insert($conversations);
    }
}