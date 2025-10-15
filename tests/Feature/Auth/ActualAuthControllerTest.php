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
        $response = $this->postJson('/api/auth/login', [
            'contact' => 'test@example.com',  // Based on actual implementation
            'password' => 'password123',
            'device_id' => 'test-device-123',
            'device_name' => 'Test Device',
            'operating_system' => 'Windows'
        ]);

        // This might succeed or return validation for other fields
        $response->assertStatus(200) // or 401 for invalid credentials
                 ->assertJson([
                     'success' => true  // or false based on credentials
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
                     'message' => 'The contact field is required. (and 4 more errors)'
                 ]);
    }
}