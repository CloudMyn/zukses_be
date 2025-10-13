<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $logs = [];
        
        for ($i = 1; $i <= 100; $i++) {
            $logs[] = [
                'id' => $i,
                'id_transaksi_pembayaran' => rand(1, 50),
                'id_user' => rand(1, 50),
                'aksi_log' => collect(['CREATE', 'UPDATE', 'DELETE', 'VIEW'])->random(),
                'deskripsi_log' => 'Deskripsi log #' . $i,
                'data_sebelumnya' => json_encode(['field' => 'value_before_' . $i]),
                'data_perubahan' => json_encode(['field' => 'value_after_' . $i]),
                'ip_address' => $this->faker->ipv4,
                'user_agent' => $this->faker->userAgent,
                'dibuat_pada' => now(),
            ];
        }

        DB::table('log_pembayaran')->insert($logs);
    }
}