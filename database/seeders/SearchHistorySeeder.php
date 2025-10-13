<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SearchHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $searches = [];
        
        for ($i = 1; $i <= 1000; $i++) {
            $searches[] = [
                'id' => $i,
                'id_user' => rand(1, 80) > 20 ? rand(1, 80) : null, // Some searches by logged in users, some by guests
                'kata_kunci_pencarian' => $this->faker->word,
                'jumlah_hasil' => rand(0, 50),
                'tanggal_pencarian' => now()->subDays(rand(0, 30))->format('Y-m-d H:i:s'),
                'ip_address' => $this->faker->ipv4,
                'user_agent' => $this->faker->userAgent,
                'sumber_pencarian' => collect(['WEB', 'MOBILE_APP', 'MOBILE_WEB'])->random(),
                'metadata' => json_encode([
                    'filters' => [
                        'category' => rand(0, 1) ? $this->faker->word : null,
                        'min_price' => rand(0, 1) ? rand(10000, 50000) : null,
                        'max_price' => rand(0, 1) ? rand(100000, 1000000) : null,
                    ],
                ]),
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ];
        }

        DB::table('riwayat_pencarian')->insert($searches);
    }
}