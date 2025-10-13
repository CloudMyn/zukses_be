<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChatReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reports = [];
        
        for ($i = 1; $i <= 100; $i++) {
            $reports[] = [
                'id' => $i,
                'id_obrolan' => rand(1, 50), // Assuming 50 conversations exist
                'id_pelapor' => rand(1, 80), // Assuming 80 users exist
                'id_pelanggar' => rand(1, 80), // Assuming 80 users exist
                'jenis_pelanggaran' => collect(['SPAM', 'KONTEN_TIDAK_SESUAI', 'PESAN_MENJIJIKKAN', 'PELECEHAN', 'LAINNYA'])->random(),
                'deskripsi_pelanggaran' => 'Deskripsi pelanggaran ' . $i,
                'bukti_pelanggaran' => rand(0, 1) ? 'https://example.com/bukti_' . $i . '.jpg' : null,
                'status_laporan' => collect(['DRAFT', 'DITERIMA', 'DITINJAU', 'DITUTUP', 'DIBERKASAKAN'])->random(),
                'tanggal_laporan' => now()->subDays(rand(0, 30))->format('Y-m-d H:i:s'),
                'tanggal_review' => rand(0, 1) ? now()->subDays(rand(0, 29))->format('Y-m-d H:i:s') : null,
                'catatan_review' => rand(0, 1) ? 'Catatan review ' . $i : null,
                'id_admin_reviewer' => rand(0, 1) ? rand(1, 10) : null, // 50% chance of having a reviewer
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ];
        }

        DB::table('laporan_obrolan')->insert($reports);
    }
}