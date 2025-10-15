<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class AuthControllerTest extends TestCase
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

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'id',
                         'username',
                         'email',
                         'tipe_user',
                         'no_hp',
                         'status',
                         'created_at',
                         'updated_at'
                     ]
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

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'errors'
                 ]);
    }

    /**
     * Test user registration with duplicate email
     */
    public function test_user_registration_with_duplicate_email(): void
    {
        // Create a user first
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->postJson('/api/auth/register', [
            'username' => 'testuser',
            'email' => 'existing@example.com',
            'kata_sandi' => 'password123',
            'konfirmasi_kata_sandi' => 'password123',
            'tipe_user' => 'PELANGGAN',
            'no_hp' => '081234567890',
            'status' => 'AKTIF'
        ]);

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'errors'
                 ]);
    }

    /**
     * Test user registration with missing required fields
     */
    public function test_user_registration_with_missing_required_fields(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'username' => 'testuser'
            // Missing required fields
        ]);

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'errors'
                 ]);
    }

    /**
     * Test user registration with invalid phone number
     */
    public function test_user_registration_with_invalid_phone_number(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'kata_sandi' => 'password123',
            'konfirmasi_kata_sandi' => 'password123',
            'tipe_user' => 'PELANGGAN',
            'no_hp' => 'invalid-phone',
            'status' => 'AKTIF'
        ]);

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'errors'
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

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'user' => [
                             'id',
                             'username',
                             'email',
                             'tipe_user',
                             'no_hp',
                             'status'
                         ],
                         'token'
                     ]
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

        $response->assertStatus(401)
                 ->assertJsonStructure([
                     'success',
                     'message'
                 ]);
    }

    /**
     * Test user login with non-existent user
     */
    public function test_user_login_with_non_existent_user(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'nonexistent@example.com',
            'kata_sandi' => 'password123'
        ]);

        $response->assertStatus(401)
                 ->assertJsonStructure([
                     'success',
                     'message'
                 ]);
    }

    /**
     * Test user login with inactive user
     */
    public function test_user_login_with_inactive_user(): void
    {
        $user = User::factory()->create([
            'email' => 'inactive@example.com',
            'kata_sandi' => bcrypt('password123'),
            'status' => 'TIDAK_AKTIF'
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'inactive@example.com',
            'kata_sandi' => 'password123'
        ]);

        $response->assertStatus(401)
                 ->assertJsonStructure([
                     'success',
                     'message'
                 ]);
    }

    /**
     * Test authenticated user details retrieval
     */
    public function test_get_authenticated_user_details(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/auth/me');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'id',
                         'username',
                         'email',
                         'tipe_user',
                         'no_hp',
                         'status'
                     ]
                 ]);
    }

    /**
     * Test user logout
     */
    public function test_user_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message'
                 ]);
    }
}