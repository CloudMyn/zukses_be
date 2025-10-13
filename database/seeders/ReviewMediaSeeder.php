<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $media = [];
        
        for ($i = 1; $i <= 300; $i++) {
            $media[] = [
                'id' => $i,
                'id_ulasan_produk' => rand(1, 150), // Assuming 150 product reviews exist
                'url_media' => 'review_media_' . $i . '.' . collect(['jpg', 'png', 'jpeg'])->random(),
                'jenis_media' => collect(['IMAGE', 'VIDEO'])->random(),
                'deskripsi_media' => rand(0, 1) ? 'Gambar produk yang diulas' : null,
                'urutan_tampilan' => rand(1, 5),
                'is_verified' => rand(0, 1),
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ];
        }

        DB::table('media_ulasan')->insert($media);
    }
}