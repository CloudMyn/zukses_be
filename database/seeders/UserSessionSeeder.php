<?php

namespace Database\Seeders;

use App\Models\UserSession;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        foreach ($users as $user) {
            UserSession::factory()->create([
                'id_user' => $user->id,
            ]);
        }
        
        UserSession::factory(10)->create();
    }
}