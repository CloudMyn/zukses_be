<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Province;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get province IDs to link cities to their provinces
        $jakartaId = Province::where('nama', 'DKI Jakarta')->first()->id;
        $jawaBaratId = Province::where('nama', 'Jawa Barat')->first()->id;
        $jawaTengahId = Province::where('nama', 'Jawa Tengah')->first()->id;
        $jawaTimurId = Province::where('nama', 'Jawa Timur')->first()->id;
        $yogyakartaId = Province::where('nama', 'DI Yogyakarta')->first()->id;
        $baliId = Province::where('nama', 'Bali')->first()->id;
        $sumateraUtaraId = Province::where('nama', 'Sumatera Utara')->first()->id;
        $riauId = Province::where('nama', 'Riau')->first()->id;
        $sumateraSelatanId = Province::where('nama', 'Sumatera Selatan')->first()->id;
        $lampungId = Province::where('nama', 'Lampung')->first()->id;

        $cities = [
            // Jakarta
            ['provinsi_id' => $jakartaId, 'nama' => 'Jakarta Pusat'],
            ['provinsi_id' => $jakartaId, 'nama' => 'Jakarta Utara'],
            ['provinsi_id' => $jakartaId, 'nama' => 'Jakarta Barat'],
            ['provinsi_id' => $jakartaId, 'nama' => 'Jakarta Selatan'],
            ['provinsi_id' => $jakartaId, 'nama' => 'Jakarta Timur'],
            
            // Jawa Barat
            ['provinsi_id' => $jawaBaratId, 'nama' => 'Bandung'],
            ['provinsi_id' => $jawaBaratId, 'nama' => 'Bekasi'],
            ['provinsi_id' => $jawaBaratId, 'nama' => 'Depok'],
            ['provinsi_id' => $jawaBaratId, 'nama' => 'Cimahi'],
            ['provinsi_id' => $jawaBaratId, 'nama' => 'Sukabumi'],
            
            // Jawa Tengah
            ['provinsi_id' => $jawaTengahId, 'nama' => 'Semarang'],
            ['provinsi_id' => $jawaTengahId, 'nama' => 'Solo'],
            ['provinsi_id' => $jawaTengahId, 'nama' => 'Yogyakarta'],
            ['provinsi_id' => $jawaTengahId, 'nama' => 'Magelang'],
            ['provinsi_id' => $jawaTengahId, 'nama' => 'Pekalongan'],
            
            // Jawa Timur
            ['provinsi_id' => $jawaTimurId, 'nama' => 'Surabaya'],
            ['provinsi_id' => $jawaTimurId, 'nama' => 'Malang'],
            ['provinsi_id' => $jawaTimurId, 'nama' => 'Madiun'],
            ['provinsi_id' => $jawaTimurId, 'nama' => 'Kediri'],
            ['provinsi_id' => $jawaTimurId, 'nama' => 'Batu'],
            
            // DI Yogyakarta
            ['provinsi_id' => $yogyakartaId, 'nama' => 'Kota Yogyakarta'],
            ['provinsi_id' => $yogyakartaId, 'nama' => 'Sleman'],
            ['provinsi_id' => $yogyakartaId, 'nama' => 'Bantul'],
            ['provinsi_id' => $yogyakartaId, 'nama' => 'Kulon Progo'],
            
            // Bali
            ['provinsi_id' => $baliId, 'nama' => 'Denpasar'],
            ['provinsi_id' => $baliId, 'nama' => 'Badung'],
            ['provinsi_id' => $baliId, 'nama' => 'Gianyar'],
            ['provinsi_id' => $baliId, 'nama' => 'Tabanan'],
            
            // Sumatera Utara
            ['provinsi_id' => $sumateraUtaraId, 'nama' => 'Medan'],
            ['provinsi_id' => $sumateraUtaraId, 'nama' => 'Pematangsiantar'],
            ['provinsi_id' => $sumateraUtaraId, 'nama' => 'Binjai'],
            ['provinsi_id' => $sumateraUtaraId, 'nama' => 'Padangsidimpuan'],
            
            // Riau
            ['provinsi_id' => $riauId, 'nama' => 'Pekanbaru'],
            ['provinsi_id' => $riauId, 'nama' => 'Dumai'],
            ['provinsi_id' => $riauId, 'nama' => 'Bagan Siapi-api'],
            
            // Sumatera Selatan
            ['provinsi_id' => $sumateraSelatanId, 'nama' => 'Palembang'],
            ['provinsi_id' => $sumateraSelatanId, 'nama' => 'Lubuklinggau'],
            ['provinsi_id' => $sumateraSelatanId, 'nama' => 'Pagar Alam'],
            
            // Lampung
            ['provinsi_id' => $lampungId, 'nama' => 'Bandar Lampung'],
            ['provinsi_id' => $lampungId, 'nama' => 'Metro'],
        ];

        foreach ($cities as $city) {
            City::create([
                'provinsi_id' => $city['provinsi_id'],
                'nama' => $city['nama'],
            ]);
        }
    }
}