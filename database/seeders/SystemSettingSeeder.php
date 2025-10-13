<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'id' => 1,
                'nama_pengaturan' => 'app_name',
                'nilai_pengaturan' => json_encode('ZUKSES Marketplace'),
                'deskripsi_pengaturan' => 'Nama aplikasi',
                'kategori_pengaturan' => 'GENERAL',
                'is_aktif' => true,
                'urutan_tampilan' => 1,
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ],
            [
                'id' => 2,
                'nama_pengaturan' => 'app_description',
                'nilai_pengaturan' => json_encode('Platform e-commerce terlengkap untuk kebutuhan Anda'),
                'deskripsi_pengaturan' => 'Deskripsi aplikasi',
                'kategori_pengaturan' => 'GENERAL',
                'is_aktif' => true,
                'urutan_tampilan' => 2,
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ],
            [
                'id' => 3,
                'nama_pengaturan' => 'maintenance_mode',
                'nilai_pengaturan' => json_encode(false),
                'deskripsi_pengaturan' => 'Mode perawatan sistem',
                'kategori_pengaturan' => 'GENERAL',
                'is_aktif' => true,
                'urutan_tampilan' => 3,
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ],
            [
                'id' => 4,
                'nama_pengaturan' => 'tax_rate',
                'nilai_pengaturan' => json_encode(11.0), // 11%
                'deskripsi_pengaturan' => 'Tarif pajak dalam persen',
                'kategori_pengaturan' => 'PAYMENT',
                'is_aktif' => true,
                'urutan_tampilan' => 4,
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ],
            [
                'id' => 5,
                'nama_pengaturan' => 'minimum_order_amount',
                'nilai_pengaturan' => json_encode(50000), // 50k
                'deskripsi_pengaturan' => 'Jumlah minimum pesanan',
                'kategori_pengaturan' => 'PAYMENT',
                'is_aktif' => true,
                'urutan_tampilan' => 5,
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ],
            [
                'id' => 6,
                'nama_pengaturan' => 'smtp_host',
                'nilai_pengaturan' => json_encode('smtp.gmail.com'),
                'deskripsi_pengaturan' => 'Host SMTP server',
                'kategori_pengaturan' => 'EMAIL',
                'is_aktif' => true,
                'urutan_tampilan' => 6,
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ],
            [
                'id' => 7,
                'nama_pengaturan' => 'smtp_port',
                'nilai_pengaturan' => json_encode(587),
                'deskripsi_pengaturan' => 'Port SMTP server',
                'kategori_pengaturan' => 'EMAIL',
                'is_aktif' => true,
                'urutan_tampilan' => 7,
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ],
            [
                'id' => 8,
                'nama_pengaturan' => 'email_verification_required',
                'nilai_pengaturan' => json_encode(true),
                'deskripsi_pengaturan' => 'Verifikasi email wajib untuk pendaftaran',
                'kategori_pengaturan' => 'SECURITY',
                'is_aktif' => true,
                'urutan_tampilan' => 8,
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ],
        ];

        DB::table('pengaturan_sistem')->insert($settings);
    }
}