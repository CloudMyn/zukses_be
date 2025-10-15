<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BasicAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if registration endpoint exists and responds properly
     */
    public function test_registration_endpoint_exists(): void
    {
        $response = $this->postJson('/api/auth/register', []);

        // Endpoint should at least return validation errors, not 500
        $response->assertStatus(422); // Should return validation error, not 500
    }

    /**
     * Test if login endpoint exists
     */
    public function test_login_endpoint_exists(): void
    {
        $response = $this->postJson('/api/auth/login', []);

        // Should return either validation errors or auth error, not 404
        $response->assertStatus(422); // Expecting validation error instead of 404
    }
}