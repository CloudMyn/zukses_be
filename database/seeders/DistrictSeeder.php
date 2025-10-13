<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\District;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get city IDs to link districts to their cities
        $jakartaPusatId = City::where('nama', 'Jakarta Pusat')->first()->id;
        $jakartaUtaraId = City::where('nama', 'Jakarta Utara')->first()->id;
        $jakartaBaratId = City::where('nama', 'Jakarta Barat')->first()->id;
        $jakartaSelatanId = City::where('nama', 'Jakarta Selatan')->first()->id;
        $jakartaTimurId = City::where('nama', 'Jakarta Timur')->first()->id;
        $bandungId = City::where('nama', 'Bandung')->first()->id;
        $surabayaId = City::where('nama', 'Surabaya')->first()->id;
        $semarangId = City::where('nama', 'Semarang')->first()->id;
        $medanId = City::where('nama', 'Medan')->first()->id;

        $districts = [
            // Jakarta Pusat
            ['kota_id' => $jakartaPusatId, 'nama' => 'Gambir'],
            ['kota_id' => $jakartaPusatId, 'nama' => 'Sawah Besar'],
            ['kota_id' => $jakartaPusatId, 'nama' => 'Kemayoran'],
            ['kota_id' => $jakartaPusatId, 'nama' => 'Senen'],
            ['kota_id' => $jakartaPusatId, 'nama' => 'Cempaka Putih'],
            ['kota_id' => $jakartaPusatId, 'nama' => 'Menteng'],
            ['kota_id' => $jakartaPusatId, 'nama' => 'Tanah Abang'],
            ['kota_id' => $jakartaPusatId, 'nama' => 'Johar Baru'],

            // Jakarta Utara
            ['kota_id' => $jakartaUtaraId, 'nama' => 'Penjaringan'],
            ['kota_id' => $jakartaUtaraId, 'nama' => 'Tanjung Priok'],
            ['kota_id' => $jakartaUtaraId, 'nama' => 'Koja'],
            ['kota_id' => $jakartaUtaraId, 'nama' => 'Cilincing'],
            ['kota_id' => $jakartaUtaraId, 'nama' => 'Pademangan'],
            ['kota_id' => $jakartaUtaraId, 'nama' => 'Kelapa Gading'],

            // Jakarta Barat
            ['kota_id' => $jakartaBaratId, 'nama' => 'Taman Sari'],
            ['kota_id' => $jakartaBaratId, 'nama' => 'Tambora'],
            ['kota_id' => $jakartaBaratId, 'nama' => 'Grogol Petamburan'],
            ['kota_id' => $jakartaBaratId, 'nama' => 'Kebon Jeruk'],
            ['kota_id' => $jakartaBaratId, 'nama' => 'Kalideres'],
            ['kota_id' => $jakartaBaratId, 'nama' => 'Palmerah'],
            ['kota_id' => $jakartaBaratId, 'nama' => 'Kembangan'],

            // Jakarta Selatan
            ['kota_id' => $jakartaSelatanId, 'nama' => 'Tebet'],
            ['kota_id' => $jakartaSelatanId, 'nama' => 'Setiabudi'],
            ['kota_id' => $jakartaSelatanId, 'nama' => 'Mampang Prapatan'],
            ['kota_id' => $jakartaSelatanId, 'nama' => 'Pasar Minggu'],
            ['kota_id' => $jakartaSelatanId, 'nama' => 'Kebayoran Lama'],
            ['kota_id' => $jakartaSelatanId, 'nama' => 'Cilandak'],
            ['kota_id' => $jakartaSelatanId, 'nama' => 'Pesanggrahan'],
            ['kota_id' => $jakartaSelatanId, 'nama' => 'Kebayoran Baru'],
            ['kota_id' => $jakartaSelatanId, 'nama' => 'Pancoran'],
            ['kota_id' => $jakartaSelatanId, 'nama' => 'Jagakarsa'],

            // Jakarta Timur
            ['kota_id' => $jakartaTimurId, 'nama' => 'Matraman'],
            ['kota_id' => $jakartaTimurId, 'nama' => 'Pulogadung'],
            ['kota_id' => $jakartaTimurId, 'nama' => 'Jatinegara'],
            ['kota_id' => $jakartaTimurId, 'nama' => 'Cakung'],
            ['kota_id' => $jakartaTimurId, 'nama' => 'Duren Sawit'],
            ['kota_id' => $jakartaTimurId, 'nama' => 'Kramat Jati'],
            ['kota_id' => $jakartaTimurId, 'nama' => 'Pasar Rebo'],
            ['kota_id' => $jakartaTimurId, 'nama' => 'Ciracas'],
            ['kota_id' => $jakartaTimurId, 'nama' => 'Makasar'],
            ['kota_id' => $jakartaTimurId, 'nama' => 'Cipayung'],

            // Bandung
            ['kota_id' => $bandungId, 'nama' => 'Bandung Kulon'],
            ['kota_id' => $bandungId, 'nama' => 'Babakan Ciparay'],
            ['kota_id' => $bandungId, 'nama' => 'Bojongloa Kaler'],
            ['kota_id' => $bandungId, 'nama' => 'Bojongloa Kidul'],
            ['kota_id' => $bandungId, 'nama' => 'Astana Anyar'],
            ['kota_id' => $bandungId, 'nama' => 'Regol'],
            ['kota_id' => $bandungId, 'nama' => 'Lengkong'],
            ['kota_id' => $bandungId, 'nama' => 'Cidadap'],

            // Surabaya
            ['kota_id' => $surabayaId, 'nama' => 'Taman'],
            ['kota_id' => $surabayaId, 'nama' => 'Sawahan'],
            ['kota_id' => $surabayaId, 'nama' => 'Wonokromo'],
            ['kota_id' => $surabayaId, 'nama' => 'Genteng'],
            ['kota_id' => $surabayaId, 'nama' => 'Gubeng'],
            ['kota_id' => $surabayaId, 'nama' => 'Sukolilo'],

            // Semarang
            ['kota_id' => $semarangId, 'nama' => 'Semarang Tengah'],
            ['kota_id' => $semarangId, 'nama' => 'Semarang Utara'],
            ['kota_id' => $semarangId, 'nama' => 'Semarang Timur'],
            ['kota_id' => $semarangId, 'nama' => 'Gayamsari'],
            ['kota_id' => $semarangId, 'nama' => 'Genuk'],

            // Medan
            ['kota_id' => $medanId, 'nama' => 'Medan Kota'],
            ['kota_id' => $medanId, 'nama' => 'Medan Sunggal'],
            ['kota_id' => $medanId, 'nama' => 'Medan Denai'],
            ['kota_id' => $medanId, 'nama' => 'Medan Area'],
            ['kota_id' => $medanId, 'nama' => 'Medan Johor'],
        ];

        foreach ($districts as $district) {
            District::create([
                'kota_id' => $district['kota_id'],
                'nama' => $district['nama'],
            ]);
        }
    }
}