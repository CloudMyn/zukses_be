<?php

namespace Tests\Feature\Api\User;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\Support\Helpers\TestHelper;

class UserControllerTest extends TestCase
{
    /**
     * Test get users list as admin - happy path
     */
    public function test_get_users_list_as_admin_happy_path(): void
    {
        // Create admin user and authenticate
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'ADMIN']);

        // Create some test users
        User::factory()->count(5)->create();

        $response = $this->withHeaders($auth['headers'])
                        ->get('/api/users');

        $this->assertApiSuccess($response, 'Data pengguna berhasil diambil');

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'username',
                    'email',
                    'nomor_telepon',
                    'tipe_user',
                    'status',
                    'created_at'
                ]
            ]
        ]);

        // Should have at least 6 users (5 created + 1 admin)
        $this->assertGreaterThanOrEqual(6, count($response->json('data')));
    }

    /**
     * Test get users list as non-admin - should be forbidden
     */
    public function test_get_users_list_as_non_admin_forbidden(): void
    {
        // Create regular user and authenticate
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'PELANGGAN']);

        $response = $this->withHeaders($auth['headers'])
                        ->get('/api/users');

        $this->assertForbidden($response, 'Anda tidak memiliki akses ke resource ini');
    }

    /**
     * Test get users list without authentication - should be unauthorized
     */
    public function test_get_users_list_without_authentication_unauthorized(): void
    {
        $response = $this->get('/api/users');

        $this->assertUnauthorized($response);
    }

    /**
     * Test get users list with search filter
     */
    public function test_get_users_list_with_search_filter(): void
    {
        // Create admin user and authenticate
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'ADMIN']);

        // Create specific users for search test
        User::factory()->create(['username' => 'testuser123']);
        User::factory()->create(['email' => 'test@example.com']);
        User::factory()->create(['nama_lengkap' => 'Test User']);

        $response = $this->withHeaders($auth['headers'])
                        ->get('/api/users?search=test');

        $this->assertApiSuccess($response);

        // Should find users with 'test' in username, email, or name
        $data = $response->json('data');
        $this->assertGreaterThan(0, count($data));
    }

    /**
     * Test get users list with status filter
     */
    public function test_get_users_list_with_status_filter(): void
    {
        // Create admin user and authenticate
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'ADMIN']);

        // Create users with different statuses
        User::factory()->create(['status' => 'AKTIF']);
        User::factory()->create(['status' => 'TIDAK_AKTIF']);

        $response = $this->withHeaders($auth['headers'])
                        ->get('/api/users?status=AKTIF');

        $this->assertApiSuccess($response);

        // All returned users should have status AKTIF
        $data = $response->json('data');
        foreach ($data as $user) {
            $this->assertEquals('AKTIF', $user['status']);
        }
    }

    /**
     * Test create user as admin - happy path
     */
    public function test_create_user_as_admin_happy_path(): void
    {
        // Create admin user and authenticate
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'ADMIN']);

        $userData = [
            'username' => 'newuser123',
            'email' => 'newuser@example.com',
            'kata_sandi' => 'password123',
            'tipe_user' => 'PELANGGAN',
            'status' => 'AKTIF',
            'nama_depan' => 'New',
            'nama_belakang' => 'User',
            'jenis_kelamin' => 'LAKI_LAKI',
            'tanggal_lahir' => '1990-01-01',
            'bio' => 'Test user bio'
        ];

        $response = $this->withHeaders($auth['headers'])
                        ->postFormData('/api/users', $userData);

        $this->assertApiCreated($response, 'Pengguna berhasil dibuat');

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'username',
                'email',
                'tipe_user',
                'status',
                'created_at'
            ]
        ]);

        // Assert user was created in database
        $this->assertDatabaseHas('users', [
            'username' => 'newuser123',
            'email' => 'newuser@example.com',
            'tipe_user' => 'PELANGGAN'
        ]);
    }

    /**
     * Test create user with phone number instead of email
     */
    public function test_create_user_with_phone_number(): void
    {
        // Create admin user and authenticate
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'ADMIN']);

        $userData = [
            'username' => 'phoneuser123',
            'nomor_telepon' => '+6281234567890',
            'kata_sandi' => 'password123',
            'tipe_user' => 'PELANGGAN',
            'status' => 'AKTIF'
        ];

        $response = $this->withHeaders($auth['headers'])
                        ->postFormData('/api/users', $userData);

        $this->assertApiCreated($response);

        $this->assertDatabaseHas('users', [
            'username' => 'phoneuser123',
            'nomor_telepon' => '+6281234567890',
            'tipe_user' => 'PELANGGAN'
        ]);
    }

    /**
     * Test create user without email or phone - should fail validation
     */
    public function test_create_user_without_email_or_phone_validation_error(): void
    {
        // Create admin user and authenticate
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'ADMIN']);

        $userData = [
            'username' => 'incompleteuser',
            'kata_sandi' => 'password123',
            'tipe_user' => 'PELANGGAN',
            'status' => 'AKTIF'
        ];

        $response = $this->withHeaders($auth['headers'])
                        ->postFormData('/api/users', $userData);

        $this->assertValidationError($response, ['contact']);
    }

    /**
     * Test create user as non-admin - should be forbidden
     */
    public function test_create_user_as_non_admin_forbidden(): void
    {
        // Create regular user and authenticate
        $auth = $this->createAuthenticatedUser();

        $userData = [
            'username' => 'unauthorizeduser',
            'email' => 'unauthorized@example.com',
            'kata_sandi' => 'password123',
            'tipe_user' => 'PELANGGAN',
            'status' => 'AKTIF'
        ];

        $response = $this->withHeaders($auth['headers'])
                        ->postFormData('/api/users', $userData);

        $this->assertForbidden($response);
    }

    /**
     * Test create user with validation errors
     */
    public function test_create_user_validation_errors(): void
    {
        // Create admin user and authenticate
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'ADMIN']);

        $userData = [
            'username' => '', // Required
            'email' => 'invalid-email', // Invalid format
            'kata_sandi' => '123', // Too short
            'tipe_user' => 'INVALID_TYPE', // Invalid enum
            'status' => 'INVALID_STATUS' // Invalid enum
        ];

        $response = $this->withHeaders($auth['headers'])
                        ->postFormData('/api/users', $userData);

        $this->assertValidationError($response, ['username', 'email', 'kata_sandi', 'tipe_user', 'status']);
    }

    /**
     * Test show specific user - happy path
     */
    public function test_show_specific_user_happy_path(): void
    {
        // Create test user
        $user = User::factory()->create([
            'username' => 'showuser',
            'email' => 'show@example.com'
        ]);

        // Create authenticated user
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'ADMIN']);

        $response = $this->withHeaders($auth['headers'])
                        ->get("/api/users/{$user->id}");

        $this->assertApiSuccess($response, 'Data pengguna berhasil diambil');

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'username',
                'email',
                'tipe_user',
                'status'
            ]
        ]);

        $response->assertJson([
            'data' => [
                'id' => $user->id,
                'username' => 'showuser',
                'email' => 'show@example.com'
            ]
        ]);
    }

    /**
     * Test show non-existent user - should return 404
     */
    public function test_show_non_existent_user_not_found(): void
    {
        // Create authenticated user
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'ADMIN']);

        $response = $this->withHeaders($auth['headers'])
                        ->get('/api/users/999999');

        $this->assertNotFound($response, 'Pengguna tidak ditemukan');
    }

    /**
     * Test update user as admin - happy path
     */
    public function test_update_user_as_admin_happy_path(): void
    {
        // Create admin user and authenticate
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'ADMIN']);

        // Create user to update
        $user = User::factory()->create([
            'username' => 'oldusername',
            'email' => 'old@example.com'
        ]);

        $updateData = [
            'username' => 'newusername',
            'email' => 'new@example.com',
            'nama_depan' => 'Updated',
            'nama_belakang' => 'User',
            'tipe_user' => 'PEDAGANG',
            'status' => 'TIDAK_AKTIF'
        ];

        $response = $this->withHeaders($auth['headers'])
                        ->putJson("/api/users/{$user->id}", $updateData);

        $this->assertApiSuccess($response, 'Pengguna berhasil diperbarui');

        // Assert changes in database
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'username' => 'newusername',
            'email' => 'new@example.com',
            'tipe_user' => 'PEDAGANG',
            'status' => 'TIDAK_AKTIF'
        ]);
    }

    /**
     * Test update user with password change
     */
    public function test_update_user_with_password_change(): void
    {
        // Create admin user and authenticate
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'ADMIN']);

        // Create user to update
        $user = User::factory()->create(['kata_sandi' => Hash::make('oldpassword')]);

        $updateData = [
            'kata_sandi' => 'newpassword123'
        ];

        $response = $this->withHeaders($auth['headers'])
                        ->putJson("/api/users/{$user->id}", $updateData);

        $this->assertApiSuccess($response);

        // Verify password was changed
        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->kata_sandi));
    }

    /**
     * Test update non-existent user - should return 404
     */
    public function test_update_non_existent_user_not_found(): void
    {
        // Create admin user and authenticate
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'ADMIN']);

        $updateData = ['username' => 'updated'];

        $response = $this->withHeaders($auth['headers'])
                        ->putJson('/api/users/999999', $updateData);

        $this->assertNotFound($response, 'Pengguna tidak ditemukan');
    }

    /**
     * Test delete user as admin - happy path
     */
    public function test_delete_user_as_admin_happy_path(): void
    {
        // Create admin user and authenticate
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'ADMIN']);

        // Create user to delete
        $user = User::factory()->create(['username' => 'tobedeleted']);

        $response = $this->withHeaders($auth['headers'])
                        ->delete("/api/users/{$user->id}");

        $this->assertApiSuccess($response, 'Pengguna berhasil dihapus');

        // Assert user is soft deleted
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    /**
     * Test delete non-existent user - should return 404
     */
    public function test_delete_non_existent_user_not_found(): void
    {
        // Create admin user and authenticate
        $auth = $this->createAuthenticatedUser(['tipe_user' => 'ADMIN']);

        $response = $this->withHeaders($auth['headers'])
                        ->delete('/api/users/999999');

        $this->assertNotFound($response, 'Pengguna tidak ditemukan');
    }

    /**
     * Test delete user without authentication - should be unauthorized
     */
    public function test_delete_user_without_authentication_unauthorized(): void
    {
        $user = User::factory()->create();

        $response = $this->delete("/api/users/{$user->id}");

        $this->assertUnauthorized($response);
    }
}