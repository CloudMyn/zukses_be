<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class ActualAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user login with valid data based on actual implementation
     */
    public function test_user_login_with_actual_required_fields(): void
    {
        // Create a test user first
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'kata_sandi' => \Illuminate\Support\Facades\Hash::make('password123')
        ]);

        $response = $this->postJson('/api/auth/login', [
            'contact' => 'test@example.com',  // Based on actual implementation
            'password' => 'password123',
            'device_id' => 'test-device-123',
            'device_name' => 'Test Device',
            'operating_system' => 'Windows'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true
                 ]);
    }

    /**
     * Test user login validation with actual required fields
     */
    public function test_user_login_validation_actual_fields(): void
    {
        $response = $this->postJson('/api/auth/login', []);

        // Should return validation errors for actual required fields
        $response->assertStatus(422)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Validasi gagal'
                 ]);
    }
}