<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChatMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $messages = [];
        
        for ($i = 1; $i <= 1000; $i++) {
            $messages[] = [
                'id' => $i,
                'id_obrolan' => rand(1, 50), // Assuming 50 conversations exist
                'id_pengirim' => rand(1, 80), // Assuming 80 users exist
                'isi_pesan' => 'Pesan #' . $i . ' - ' . $this->faker->sentence,
                'jenis_pesan' => collect(['TEKS', 'GAMBAR', 'VIDEO', 'DOKUMEN', 'LOKASI'])->random(),
                'id_pesan_induk' => rand(1, 100) > 80 ? rand(1, min($i-1, 100)) : null, // ~20% chance of being reply
                'tanggal_pesan' => now()->subMinutes(rand(0, 10080))->format('Y-m-d H:i:s'), // Up to 1 week ago
                'dibaca_pada' => rand(0, 1) ? now()->subMinutes(rand(0, 10079))->format('Y-m-d H:i:s') : null,
                'ditarik_pada' => rand(1, 100) > 95 ? now()->subMinutes(rand(0, 10078))->format('Y-m-d H:i:s') : null, // ~5% withdrawn
                'jumlah_baca' => rand(0, 30),
                'jumlah_diteruskan' => rand(0, 5),
                'metadata' => json_encode([
                    'file_info' => [
                        'name' => 'file_' . $i . '.jpg',
                        'size' => rand(1000, 1000000),
                        'mime_type' => 'image/jpeg'
                    ]
                ]),
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ];
        }

        DB::table('pesan_obrolan')->insert($messages);
    }
}