<?php

namespace Database\Seeders;

use App\Models\Verification;
use App\Models\User;
use Illuminate\Database\Seeder;

class VerificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        foreach ($users as $user) {
            Verification::factory()->create([
                'id_user' => $user->id,
                'jenis_verifikasi' => 'EMAIL',
                'nilai_verifikasi' => $user->email,
            ]);
            
            Verification::factory()->create([
                'id_user' => $user->id,
                'jenis_verifikasi' => 'TELEPON',
                'nilai_verifikasi' => $user->nomor_telepon,
            ]);
        }
        
        Verification::factory(10)->create();
    }
}