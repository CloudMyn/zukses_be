<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            ['nama' => 'Aceh'],
            ['nama' => 'Sumatera Utara'],
            ['nama' => 'Sumatera Barat'],
            ['nama' => 'Riau'],
            ['nama' => 'Jambi'],
            ['nama' => 'Sumatera Selatan'],
            ['nama' => 'Bengkulu'],
            ['nama' => 'Lampung'],
            ['nama' => 'Kepulauan Bangka Belitung'],
            ['nama' => 'Kepulauan Riau'],
            ['nama' => 'DKI Jakarta'],
            ['nama' => 'Jawa Barat'],
            ['nama' => 'Jawa Tengah'],
            ['nama' => 'DI Yogyakarta'],
            ['nama' => 'Jawa Timur'],
            ['nama' => 'Banten'],
            ['nama' => 'Bali'],
            ['nama' => 'Nusa Tenggara Barat'],
            ['nama' => 'Nusa Tenggara Timur'],
            ['nama' => 'Kalimantan Barat'],
            ['nama' => 'Kalimantan Tengah'],
            ['nama' => 'Kalimantan Selatan'],
            ['nama' => 'Kalimantan Timur'],
            ['nama' => 'Kalimantan Utara'],
            ['nama' => 'Sulawesi Utara'],
            ['nama' => 'Sulawesi Tengah'],
            ['nama' => 'Sulawesi Selatan'],
            ['nama' => 'Sulawesi Tenggara'],
            ['nama' => 'Gorontalo'],
            ['nama' => 'Sulawesi Barat'],
            ['nama' => 'Maluku'],
            ['nama' => 'Maluku Utara'],
            ['nama' => 'Papua'],
            ['nama' => 'Papua Barat'],
            ['nama' => 'Papua Tengah'],
            ['nama' => 'Papua Pegunungan'],
            ['nama' => 'Papua Selatan'],
            ['nama' => 'Papua Barat Daya'],
            ['nama' => 'Kalimantan Barat'],
            ['nama' => 'Nanggroe Aceh Darussalam (NAD)'],
        ];

        foreach ($provinces as $province) {
            Province::create([
                'nama' => $province['nama'],
            ]);
        }
    }
}