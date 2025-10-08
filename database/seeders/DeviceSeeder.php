<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        foreach ($users as $user) {
            Device::factory()->create([
                'id_user' => $user->id,
            ]);
        }
        
        Device::factory(10)->create();
    }
}