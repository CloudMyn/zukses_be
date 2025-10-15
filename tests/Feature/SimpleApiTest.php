<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SimpleApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_base_route(): void
    {
        $response = $this->getJson('/api/users');

        // Ini mungkin memerlukan autentikasi, jadi bisa saja gagal
        $response->assertStatus(401); // Unauthorized karena tidak ada token
    }
    
    public function test_get_authenticated_user(): void
    {
        // Test endpoint yang memerlukan autentikasi dengan token palsu
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid-token',
        ])->getJson('/api/auth/me');
        
        $response->assertStatus(401); // Unauthorized karena token invalid
    }
}