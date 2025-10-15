<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class WorkingAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user registration form validation
     */
    public function test_user_registration_validation(): void
    {
        $response = $this->postJson('/api/auth/register', []);

        // Test that registration endpoint returns validation errors
        $response->assertStatus(422)
                 ->assertJson([
                     'success' => false
                 ]);
    }

    /**
     * Test user login validation
     */
    public function test_user_login_validation(): void
    {
        $response = $this->postJson('/api/auth/login', []);

        // Test that login endpoint returns validation errors
        $response->assertStatus(422)
                 ->assertJson([
                     'success' => false
                 ]);
    }

    /**
     * Test unauthorized access to protected endpoint
     */
    public function test_unauthorized_access_to_protected_endpoint(): void
    {
        $response = $this->getJson('/api/auth/me');

        // Should return 401 for unauthorized access
        $response->assertStatus(401);
    }

    /**
     * Test google auth redirect
     */
    public function test_google_auth_redirect(): void
    {
        // Mock Socialite to avoid session dependency
        Socialite::shouldReceive('driver')
                 ->with('google')
                 ->andReturnSelf();

        Socialite::shouldReceive('redirect')
                 ->andReturn(redirect('https://accounts.google.com/oauth/authorize'));

        $response = $this->getJson('/api/auth/google');

        // Should return redirect response
        $response->assertStatus(302);
    }
}