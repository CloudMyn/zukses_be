<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUsers = [];
        
        for ($i = 1; $i <= 10; $i++) {
            $adminUsers[] = [
                'id' => $i,
                'id_user' => $i, // Assuming first 10 users are admin users
                'nip' => 'NIP-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'nama_lengkap' => 'Admin ' . $i,
                'nomor_telepon' => '+62812' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'departemen' => collect(['IT', 'HR', 'Finance', 'Operations', 'Marketing'])->random(),
                'jabatan' => collect(['Staff', 'Supervisor', 'Manager'])->random(),
                'tanggal_mulai_bekerja' => now()->subYears(rand(1, 5))->format('Y-m-d'),
                'is_active' => true,
                'catatan' => 'Admin user ' . $i,
                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ];
        }

        DB::table('admin_users')->insert($adminUsers);
    }
}