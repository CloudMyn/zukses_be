<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test listing all users (admin)
     */
    public function test_list_all_users_as_admin(): void
    {
        // Create an admin user and authenticate
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'ADMIN']);
        $token = $auth['token'];

        // Create some additional users
        User::factory()->count(5)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/users');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         '*' => [
                             'id',
                             'username',
                             'email',
                             'tipe_user',
                             'no_hp',
                             'status',
                             'created_at',
                             'updated_at'
                         ]
                     ],
                     'pagination' => [
                         'current_page',
                         'last_page',
                         'per_page',
                         'total'
                     ]
                 ]);
    }

    /**
     * Test listing users as regular user (should be forbidden)
     */
    public function test_list_users_as_regular_user_is_forbidden(): void
    {
        // Create a regular user and authenticate
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'PELANGGAN']);
        $token = $auth['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/users');

        $response->assertStatus(403);
    }

    /**
     * Test pagination functionality for users
     */
    public function test_users_pagination(): void
    {
        // Create an admin user and authenticate
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'ADMIN']);
        $token = $auth['token'];

        // Create more users than the default page size
        User::factory()->count(25)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/users?page=1&per_page=10');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data',
                     'pagination' => [
                         'current_page',
                         'last_page',
                         'per_page',
                         'total'
                     ]
                 ])
                 ->assertJsonCount(10, 'data');
    }

    /**
     * Test search functionality for users
     */
    public function test_users_search_functionality(): void
    {
        // Create an admin user and authenticate
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'ADMIN']);
        $token = $auth['token'];

        // Create users with specific names for searching
        User::factory()->create(['username' => 'john_doe']);
        User::factory()->create(['username' => 'jane_smith']);
        User::factory()->create(['username' => 'bob_johnson']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/users?search=john');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data',
                     'pagination'
                 ]);
        // At least john_doe and bob_johnson should match
        $this->assertGreaterThanOrEqual(2, count($response->json('data')));
    }

    /**
     * Test filter by status for users
     */
    public function test_users_filter_by_status(): void
    {
        // Create an admin user and authenticate
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'ADMIN']);
        $token = $auth['token'];

        // Create users with different statuses
        User::factory()->create(['status' => 'AKTIF']);
        User::factory()->create(['status' => 'TIDAK_AKTIF']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/users?status=AKTIF');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data',
                     'pagination'
                 ]);
    }

    /**
     * Test creating user with valid data
     */
    public function test_create_user_with_valid_data(): void
    {
        // Create an admin user and authenticate
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'ADMIN']);
        $token = $auth['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/users', [
            'username' => 'newuser',
            'email' => 'newuser@example.com',
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
                         'status'
                     ]
                 ]);
    }

    /**
     * Test creating user with duplicate email
     */
    public function test_create_user_with_duplicate_email(): void
    {
        // Create an admin user and authenticate
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'ADMIN']);
        $token = $auth['token'];

        // Create a user first
        $existingUser = User::factory()->create(['email' => 'existing@example.com']);

        // Try to create another user with the same email
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/users', [
            'username' => 'newuser',
            'email' => 'existing@example.com',  // Same as existing user
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
     * Test creating user with invalid data
     */
    public function test_create_user_with_invalid_data(): void
    {
        // Create an admin user and authenticate
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'ADMIN']);
        $token = $auth['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/users', [
            'username' => '',  // Invalid - empty
            'email' => 'invalid-email',  // Invalid email format
            'kata_sandi' => '123',  // Too short
            'konfirmasi_kata_sandi' => 'different',  // Doesn't match
            'tipe_user' => 'INVALID_TYPE',  // Invalid type
            'no_hp' => 'invalid-phone',  // Invalid phone
            'status' => 'INVALID_STATUS'  // Invalid status
        ]);

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'errors'
                 ]);
    }

    /**
     * Test creating user with missing required fields
     */
    public function test_create_user_with_missing_required_fields(): void
    {
        // Create an admin user and authenticate
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'ADMIN']);
        $token = $auth['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/users', [
            'username' => 'newuser'
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
     * Test getting own profile
     */
    public function test_get_own_profile(): void
    {
        $auth = $this->createAuthenticatedUser();
        $user = $auth['user'];
        $token = $auth['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/users/{$user->id}");

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
     * Test getting other user profile
     */
    public function test_get_other_user_profile(): void
    {
        // Create two users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $auth = $this->createAuthenticatedUser($user1->toArray());
        $token = $auth['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/users/{$user2->id}");

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
     * Test getting non-existent user
     */
    public function test_get_non_existent_user(): void
    {
        $auth = $this->createAuthenticatedUser();
        $token = $auth['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/users/999999');  // Non-existent user ID

        $response->assertStatus(404)
                 ->assertJsonStructure([
                     'success',
                     'message'
                 ]);
    }

    /**
     * Test updating own profile
     */
    public function test_update_own_profile(): void
    {
        $auth = $this->createAuthenticatedUser();
        $user = $auth['user'];
        $token = $auth['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/users/{$user->id}", [
            'username' => 'updated_username',
            'email' => 'updated@example.com',
            'no_hp' => '081234567899',
            'status' => 'AKTIF'
        ]);

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
     * Test updating other user profile (admin)
     */
    public function test_update_other_user_profile_as_admin(): void
    {
        // Create admin user
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'ADMIN']);
        $adminToken = $auth['token'];

        // Create another user to update
        $user = User::factory()->create(['username' => 'original_username']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $adminToken,
        ])->putJson("/api/users/{$user->id}", [
            'username' => 'updated_by_admin',
            'email' => 'updated_by_admin@example.com',
            'no_hp' => '081234567899',
            'status' => 'TIDAK_AKTIF'
        ]);

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
     * Test updating with invalid data
     */
    public function test_update_with_invalid_data(): void
    {
        $auth = $this->createAuthenticatedUser();
        $user = $auth['user'];
        $token = $auth['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/users/{$user->id}", [
            'email' => 'invalid-email-format',
            'no_hp' => 'invalid-phone'
        ]);

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'errors'
                 ]);
    }

    /**
     * Test deleting own account
     */
    public function test_delete_own_account(): void
    {
        $auth = $this->createAuthenticatedUser();
        $user = $auth['user'];
        $token = $auth['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message'
                 ]);
    }

    /**
     * Test deleting user as admin
     */
    public function test_delete_user_as_admin(): void
    {
        // Create admin user
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'ADMIN']);
        $adminToken = $auth['token'];

        // Create another user to delete
        $user = User::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $adminToken,
        ])->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message'
                 ]);
    }

    /**
     * Test deleting non-existent user
     */
    public function test_delete_non_existent_user(): void
    {
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'ADMIN']);
        $token = $auth['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson('/api/users/999999');  // Non-existent user ID

        $response->assertStatus(404)
                 ->assertJsonStructure([
                     'success',
                     'message'
                 ]);
    }
}