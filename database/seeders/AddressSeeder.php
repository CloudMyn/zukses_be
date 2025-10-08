<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        foreach ($users as $user) {
            Address::factory()->create([
                'id_user' => $user->id,
                'adalah_alamat_utama' => true,
            ]);
            
            Address::factory(2)->create([
                'id_user' => $user->id,
            ]);
        }
        
        Address::factory(20)->create();
    }
}