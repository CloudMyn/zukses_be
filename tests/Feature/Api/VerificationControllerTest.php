<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\Traits\ApiTestTrait;
use App\Models\User;
use App\Models\Verification;

class VerificationControllerTest extends TestCase
{
    use RefreshDatabase;
    use ApiTestTrait;

    /**
     * Test listing verification attempts
     */
    public function test_list_verification_attempts(): void
    {
        // Create and authenticate a user
        $auth = $this->createAuthenticatedUser();
        $user = $auth['user'];
        $token = $auth['token'];

        // Create some verification attempts for the user
        Verification::factory()->count(3)->create(['id_user' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/verifications');

        $response->assertStatus(200);

        // Assert response structure
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'id_user',
                    'jenis_verifikasi',
                    'nomor_verifikasi',
                    'kode_verifikasi',
                    'waktu_kadaluarsa',
                    'status_verifikasi',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
    }

    /**
     * Test creating verification request
     */
    public function test_create_verification_request(): void
    {
        // Create and authenticate a user
        $auth = $this->createAuthenticatedUser();
        $user = $auth['user'];
        $token = $auth['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/verifications', [
            'jenis_verifikasi' => 'EMAIL',
            'nomor_verifikasi' => 'test@example.com',
            'nilai_verifikasi' => '123456',
            'kode_verifikasi' => 'TEST123',
            'kedaluwarsa_pada' => '2023-01-01 00:00:00',
            'telah_digunakan' => true,
            'jumlah_coba' => 0
        ]);

        $response->assertStatus(201);
    }

    /**
     * Test updating verification status
     */
    public function test_update_verification_status(): void
    {
        // Create and authenticate a user
        $auth = $this->createAuthenticatedUser();
        $user = $auth['user'];
        $token = $auth['token'];

        // Create a verification record
        $verification = Verification::factory()->create([
            'id_user' => $user->id,
            'jenis_verifikasi' => 'EMAIL',
            'status_verifikasi' => 'BELUM_DIPROSES'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/verifications/{$verification->id}", [
            'status_verifikasi' => 'DIPROSES'
        ]);

        $response->assertStatus(200);
    }

    /**
     * Test getting verification details
     */
    public function test_get_verification_details(): void
    {
        // Create and authenticate a user
        $auth = $this->createAuthenticatedUser();
        $user = $auth['user'];
        $token = $auth['token'];

        // Create a verification record
        $verification = Verification::factory()->create(['id_user' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/verifications/{$verification->id}");

        $response->assertStatus(200);
    }
}