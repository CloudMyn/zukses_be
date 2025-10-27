<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Promosi;
use App\Models\User;
use App\Models\CategoryProduct;
use App\Models\Product;
use Tymon\JWTAuth\Facades\JWTAuth;

class PromosiControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $regularUser;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'tipe_user' => 'ADMIN'
        ]);

        $this->regularUser = User::factory()->create([
            'tipe_user' => 'PELANGGAN'
        ]);
    }

    public function test_admin_can_create_promotion()
    {
        $this->token = JWTAuth::fromUser($this->adminUser);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/promotions', [
            'kode_promosi' => 'TEST10',
            'nama_promosi' => 'Test Promotion',
            'deskripsi' => 'Test description',
            'jenis_promosi' => 'KODE_PROMOSI',
            'tipe_diskon' => 'PERSEN',
            'nilai_diskon' => 10,
            'jumlah_maksimum_penggunaan' => 100,
            'jumlah_maksimum_penggunaan_per_pengguna' => 1,
            'tanggal_mulai' => now()->format('Y-m-d H:i:s'),
            'tanggal_berakhir' => now()->addDays(30)->format('Y-m-d H:i:s'),
            'minimum_pembelian' => 50000,
            'dapat_digabungkan' => true,
            'status_aktif' => true,
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Promosi berhasil dibuat',
                 ]);

        $this->assertDatabaseHas('tb_promosi', [
            'kode_promosi' => 'TEST10',
            'nama_promosi' => 'Test Promotion',
            'jenis_promosi' => 'KODE_PROMOSI',
        ]);
    }

    public function test_regular_user_cannot_create_promotion()
    {
        $this->token = JWTAuth::fromUser($this->regularUser);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/promotions', [
            'kode_promosi' => 'TEST10',
            'nama_promosi' => 'Test Promotion',
            'jenis_promosi' => 'KODE_PROMOSI',
            'tipe_diskon' => 'PERSEN',
            'nilai_diskon' => 10,
            'jumlah_maksimum_penggunaan' => 100,
            'jumlah_maksimum_penggunaan_per_pengguna' => 1,
            'tanggal_mulai' => now()->format('Y-m-d H:i:s'),
            'tanggal_berakhir' => now()->addDays(30)->format('Y-m-d H:i:s'),
            'minimum_pembelian' => 50000,
            'dapat_digabungkan' => true,
            'status_aktif' => true,
        ]);

        $response->assertStatus(403);
    }

    public function test_validation_fails_with_invalid_data()
    {
        $this->token = JWTAuth::fromUser($this->adminUser);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/promotions', [
            'kode_promosi' => '', // Required field
            'nama_promosi' => '', // Required field
            'jenis_promosi' => 'INVALID', // Invalid enum
            'tipe_diskon' => 'INVALID', // Invalid enum
            'nilai_diskon' => -10, // Invalid value
            'jumlah_maksimum_penggunaan' => -1, // Invalid value
            'jumlah_maksimum_penggunaan_per_pengguna' => -1, // Invalid value
            'tanggal_mulai' => 'invalid-date', // Invalid date
            'tanggal_berakhir' => 'invalid-date', // Invalid date
            'minimum_pembelian' => -1, // Invalid value
            'dapat_digabungkan' => 'not-boolean', // Invalid boolean
            'status_aktif' => 'not-boolean', // Invalid boolean
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    }

    public function test_admin_can_get_all_promotions()
    {
        $this->token = JWTAuth::fromUser($this->adminUser);

        Promosi::factory(5)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/promotions');

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data.data');
    }

    public function test_regular_user_can_get_active_promotions()
    {
        $this->token = JWTAuth::fromUser($this->regularUser);

        Promosi::factory()->create(['status_aktif' => true]);
        Promosi::factory()->create(['status_aktif' => false]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/promotions');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data.data');
    }

    public function test_admin_can_get_single_promotion()
    {
        $this->token = JWTAuth::fromUser($this->adminUser);

        $promosi = Promosi::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/promotions/{$promosi->id}");

        $response->assertStatus(200);
        $response->assertJson(['data' => ['id' => $promosi->id]]);
    }

    public function test_admin_can_update_promotion()
    {
        $this->token = JWTAuth::fromUser($this->adminUser);

        $promosi = Promosi::factory()->create();

        $updateData = ['nama_promosi' => 'Updated Name'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/promotions/{$promosi->id}", $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tb_promosi', array_merge(['id' => $promosi->id], $updateData));
    }

    public function test_admin_can_delete_promotion()
    {
        $this->token = JWTAuth::fromUser($this->adminUser);

        $promosi = Promosi::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/promotions/{$promosi->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('tb_promosi', ['id' => $promosi->id]);
    }

    public function test_promotion_validation_endpoint_works()
    {
        $this->token = JWTAuth::fromUser($this->adminUser);

        $promosi = Promosi::factory()->create([
            'kode_promosi' => 'VALID10',
            'status_aktif' => true,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_berakhir' => now()->addDay(),
            'minimum_pembelian' => 50000,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/promotions/validate', [
            'kode_promosi' => 'VALID10',
            'total_pembelian' => 100000,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    public function test_promotion_validation_fails_for_inactive_promo()
    {
        $this->token = JWTAuth::fromUser($this->adminUser);

        $promosi = Promosi::factory()->create([
            'kode_promosi' => 'INACTIVE',
            'status_aktif' => false,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/promotions/validate', [
            'kode_promosi' => 'INACTIVE',
            'total_pembelian' => 100000,
        ]);

        $response->assertStatus(400);
        $response->assertJson(['success' => false]);
    }

    public function test_promotion_validation_fails_for_expired_promo()
    {
        $this->token = JWTAuth::fromUser($this->adminUser);

        $promosi = Promosi::factory()->create([
            'kode_promosi' => 'EXPIRED',
            'tanggal_berakhir' => now()->subDay(),
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/promotions/validate', [
            'kode_promosi' => 'EXPIRED',
            'total_pembelian' => 100000,
        ]);

        $response->assertStatus(400);
        $response->assertJson(['success' => false]);
    }

    public function test_promotion_validation_fails_for_insufficient_purchase()
    {
        $this->token = JWTAuth::fromUser($this->adminUser);

        $promosi = Promosi::factory()->create([
            'kode_promosi' => 'MIN_PURCHASE',
            'minimum_pembelian' => 100000,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/promotions/validate', [
            'kode_promosi' => 'MIN_PURCHASE',
            'total_pembelian' => 50000,
        ]);

        $response->assertStatus(400);
        $response->assertJson(['success' => false]);
    }

    public function test_promotion_validation_fails_for_nonexistent_promo()
    {
        $this->token = JWTAuth::fromUser($this->adminUser);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/promotions/validate', [
            'kode_promosi' => 'NONEXISTENT',
            'total_pembelian' => 100000,
        ]);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }
}