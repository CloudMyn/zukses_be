<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Verification;

class VerificationControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test listing verification attempts
     */
    public function test_list_verification_attempts(): void
    {
        $auth = $this->createAuthenticatedUser();
        $user = $auth['user'];
        $token = $auth['token'];

        // Create some verification attempts for the user
        Verification::factory()->count(3)->create(['id_user' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/verifications');

        $response->assertStatus(200)
                 ->assertJsonStructure([
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
     * Test creating verification request
     */
    public function test_create_verification_request(): void
    {
        $auth = $this->createAuthenticatedUser();
        $user = $auth['user'];
        $token = $auth['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/verifications', [
            'jenis_verifikasi' => 'EMAIL',
            'nomor_verifikasi' => 'test@example.com'
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'id',
                         'id_user',
                         'jenis_verifikasi',
                         'nomor_verifikasi',
                         'kode_verifikasi', // This might be masked in response
                         'waktu_kadaluarsa',
                         'status_verifikasi',
                         'created_at',
                         'updated_at'
                     ]
                 ]);
    }

    /**
     * Test updating verification status
     */
    public function test_update_verification_status(): void
    {
        $auth = $this->createAuthenticatedUser();
        $user = $auth['user'];
        $token = $auth['token'];
        
        $verification = Verification::factory()->create([
            'id_user' => $user->id,
            'status_verifikasi' => 'BELUM_DIPROSES'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/verifications/{$verification->id}", [
            'status_verifikasi' => 'DIPROSES'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
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
                 ]);
    }

    /**
     * Test getting verification details
     */
    public function test_get_verification_details(): void
    {
        $auth = $this->createAuthenticatedUser();
        $user = $auth['user'];
        $token = $auth['token'];
        
        $verification = Verification::factory()->create(['id_user' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/verifications/{$verification->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
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
                 ]);
    }
}