<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class SimpleAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user registration with valid data
     */
    public function test_user_registration_with_valid_data(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'kata_sandi' => 'password123',
            'konfirmasi_kata_sandi' => 'password123',
            'tipe_user' => 'PELANGGAN',
            'no_hp' => '081234567890',
            'status' => 'AKTIF'
        ]);

        // Ubah ekspektasi berdasarkan implementasi aktual
        $response->assertStatus(200) // atau 201 tergantung implementasi
                 ->assertJson([
                     'success' => true
                 ]);
    }

    /**
     * Test user registration with invalid email
     */
    public function test_user_registration_with_invalid_email(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'username' => 'testuser',
            'email' => 'invalid-email',
            'kata_sandi' => 'password123',
            'konfirmasi_kata_sandi' => 'password123',
            'tipe_user' => 'PELANGGAN',
            'no_hp' => '081234567890',
            'status' => 'AKTIF'
        ]);

        $response->assertStatus(422) // Validation error
                 ->assertJson([
                     'success' => false
                 ]);
    }

    /**
     * Test user login with valid credentials
     */
    public function test_user_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'kata_sandi' => bcrypt('password123'),
            'status' => 'AKTIF'
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'login@example.com',
            'kata_sandi' => 'password123'
        ]);

        $response->assertStatus(200) // atau status lain tergantung implementasi
                 ->assertJson([
                     'success' => true
                 ]);
    }

    /**
     * Test user login with invalid credentials
     */
    public function test_user_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'kata_sandi' => bcrypt('password123'),
            'status' => 'AKTIF'
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'login@example.com',
            'kata_sandi' => 'wrongpassword'
        ]);

        $response->assertStatus(401) // Unauthorized
                 ->assertJson([
                     'success' => false
                 ]);
    }
}