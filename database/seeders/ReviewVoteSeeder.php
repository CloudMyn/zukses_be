<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewVoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $votes = [];
        
        for ($i = 1; $i <= 500; $i++) {
            $votes[] = [
                'id' => $i,
                'id_ulasan_produk' => rand(1, 150), // Assuming 150 product reviews exist
                'id_user' => rand(1, 80), // Assuming 80 users exist
                'jenis_vote' => collect(['SUKA', 'TIDAK_SUKA'])->random(),
                'tanggal_vote' => now()->subDays(rand(0, 30))->format('Y-m-d H:i:s'),
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ];
        }

        DB::table('vote_ulasan')->insert($votes);
    }
}