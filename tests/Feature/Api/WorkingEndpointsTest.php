<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WorkingEndpointsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user listing endpoint (requires auth)
     */
    public function test_user_listing_requires_authentication(): void
    {
        $response = $this->getJson('/api/users');

        // Should return 401 for unauthorized access
        $response->assertStatus(401);
    }

    /**
     * Test that protected auth endpoint requires authentication
     */
    public function test_me_endpoint_requires_authentication(): void
    {
        $response = $this->getJson('/api/auth/me');

        $response->assertStatus(401);
    }

    /**
     * Test that logout endpoint requires authentication
     */
    public function test_logout_endpoint_requires_authentication(): void
    {
        $response = $this->postJson('/api/auth/logout');

        $response->assertStatus(401);
    }
}