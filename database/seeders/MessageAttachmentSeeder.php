<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageAttachmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attachments = [];
        
        for ($i = 1; $i <= 500; $i++) {
            $attachments[] = [
                'id' => $i,
                'id_pesan' => rand(1, 500), // Assuming 500 messages exist
                'nama_file' => 'file_' . $i . '.' . collect(['jpg', 'png', 'pdf', 'mp4'])->random(),
                'path_file' => 'attachments/file_' . $i . '.' . collect(['jpg', 'png', 'pdf', 'mp4'])->random(),
                'url_file' => 'https://example.com/attachments/file_' . $i . '.' . collect(['jpg', 'png', 'pdf', 'mp4'])->random(),
                'jenis_file' => collect(['GAMBAR', 'VIDEO', 'AUDIO', 'DOKUMEN', 'LAINNYA'])->random(),
                'ukuran_file' => rand(1000, 5000000),
                'mime_type' => collect(['image/jpeg', 'image/png', 'video/mp4', 'audio/mpeg', 'application/pdf'])->random(),
                'deskripsi_file' => rand(0, 1) ? 'Deskripsi file ' . $i : null,
                'is_thumbnail' => rand(0, 1),
                'urutan_tampilan' => rand(1, 5),
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ];
        }

        DB::table('lampiran_pesan')->insert($attachments);
    }
}