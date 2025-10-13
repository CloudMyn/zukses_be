<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\PostalCode;
use Illuminate\Database\Seeder;

class PostalCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get district IDs to link postal codes to their districts
        $gambirId = District::where('nama', 'Gambir')->first()->id;
        $sawahBesarId = District::where('nama', 'Sawah Besar')->first()->id;
        $kemayoranId = District::where('nama', 'Kemayoran')->first()->id;
        $mentengId = District::where('nama', 'Menteng')->first()->id;
        $penjaringanId = District::where('nama', 'Penjaringan')->first()->id;
        $tanjungPriokId = District::where('nama', 'Tanjung Priok')->first()->id;
        $tamanSariId = District::where('nama', 'Taman Sari')->first()->id;
        $tamboraId = District::where('nama', 'Tambora')->first()->id;
        $tebetId = District::where('nama', 'Tebet')->first()->id;
        $setiabudiId = District::where('nama', 'Setiabudi')->first()->id;
        $bandungKulonId = District::where('nama', 'Bandung Kulon')->first()->id;
        $babakanCiparayId = District::where('nama', 'Babakan Ciparay')->first()->id;
        $tamanId = District::where('nama', 'Taman')->first()->id;
        $sawahanId = District::where('nama', 'Sawahan')->first()->id;
        $medanKotaId = District::where('nama', 'Medan Kota')->first()->id;
        $medanSunggalId = District::where('nama', 'Medan Sunggal')->first()->id;

        $postalCodes = [
            // Jakarta Pusat - Gambir
            ['kecamatan_id' => $gambirId, 'kode' => '10110'],
            ['kecamatan_id' => $gambirId, 'kode' => '10120'],
            ['kecamatan_id' => $gambirId, 'kode' => '10130'],
            
            // Jakarta Pusat - Sawah Besar
            ['kecamatan_id' => $sawahBesarId, 'kode' => '10710'],
            ['kecamatan_id' => $sawahBesarId, 'kode' => '10720'],
            
            // Jakarta Pusat - Kemayoran
            ['kecamatan_id' => $kemayoranId, 'kode' => '10610'],
            ['kecamatan_id' => $kemayoranId, 'kode' => '10620'],
            ['kecamatan_id' => $kemayoranId, 'kode' => '10630'],
            
            // Jakarta Pusat - Menteng
            ['kecamatan_id' => $mentengId, 'kode' => '10310'],
            ['kecamatan_id' => $mentengId, 'kode' => '10320'],
            ['kecamatan_id' => $mentengId, 'kode' => '10330'],
            
            // Jakarta Utara - Penjaringan
            ['kecamatan_id' => $penjaringanId, 'kode' => '14420'],
            ['kecamatan_id' => $penjaringanId, 'kode' => '14430'],
            ['kecamatan_id' => $penjaringanId, 'kode' => '14440'],
            
            // Jakarta Utara - Tanjung Priok
            ['kecamatan_id' => $tanjungPriokId, 'kode' => '14310'],
            ['kecamatan_id' => $tanjungPriokId, 'kode' => '14320'],
            ['kecamatan_id' => $tanjungPriokId, 'kode' => '14330'],
            ['kecamatan_id' => $tanjungPriokId, 'kode' => '14340'],
            
            // Jakarta Barat - Taman Sari
            ['kecamatan_id' => $tamanSariId, 'kode' => '11110'],
            ['kecamatan_id' => $tamanSariId, 'kode' => '11120'],
            
            // Jakarta Barat - Tambora
            ['kecamatan_id' => $tamboraId, 'kode' => '11210'],
            ['kecamatan_id' => $tamboraId, 'kode' => '11220'],
            ['kecamatan_id' => $tamboraId, 'kode' => '11230'],
            
            // Jakarta Selatan - Tebet
            ['kecamatan_id' => $tebetId, 'kode' => '12810'],
            ['kecamatan_id' => $tebetId, 'kode' => '12820'],
            ['kecamatan_id' => $tebetId, 'kode' => '12830'],
            
            // Jakarta Selatan - Setiabudi
            ['kecamatan_id' => $setiabudiId, 'kode' => '12910'],
            ['kecamatan_id' => $setiabudiId, 'kode' => '12920'],
            ['kecamatan_id' => $setiabudiId, 'kode' => '12930'],
            
            // Bandung - Bandung Kulon
            ['kecamatan_id' => $bandungKulonId, 'kode' => '40211'],
            ['kecamatan_id' => $bandungKulonId, 'kode' => '40212'],
            ['kecamatan_id' => $bandungKulonId, 'kode' => '40213'],
            
            // Bandung - Babakan Ciparay
            ['kecamatan_id' => $babakanCiparayId, 'kode' => '40222'],
            ['kecamatan_id' => $babakanCiparayId, 'kode' => '40223'],
            ['kecamatan_id' => $babakanCiparayId, 'kode' => '40224'],
            
            // Surabaya - Taman
            ['kecamatan_id' => $tamanId, 'kode' => '60131'],
            ['kecamatan_id' => $tamanId, 'kode' => '60132'],
            ['kecamatan_id' => $tamanId, 'kode' => '60133'],
            
            // Surabaya - Sawahan
            ['kecamatan_id' => $sawahanId, 'kode' => '60251'],
            ['kecamatan_id' => $sawahanId, 'kode' => '60252'],
            ['kecamatan_id' => $sawahanId, 'kode' => '60253'],
            
            // Medan - Medan Kota
            ['kecamatan_id' => $medanKotaId, 'kode' => '20111'],
            ['kecamatan_id' => $medanKotaId, 'kode' => '20112'],
            ['kecamatan_id' => $medanKotaId, 'kode' => '20113'],
            
            // Medan - Medan Sunggal
            ['kecamatan_id' => $medanSunggalId, 'kode' => '20113'],
            ['kecamatan_id' => $medanSunggalId, 'kode' => '20114'],
            ['kecamatan_id' => $medanSunggalId, 'kode' => '20115'],
        ];

        foreach ($postalCodes as $postalCode) {
            PostalCode::create([
                'kecamatan_id' => $postalCode['kecamatan_id'],
                'kode' => $postalCode['kode'],
            ]);
        }
    }
}