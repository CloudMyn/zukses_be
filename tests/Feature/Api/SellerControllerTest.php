<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Seller;

class SellerControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test listing all sellers
     */
    public function test_list_all_sellers(): void
    {
        // Create a few seller users and sellers
        $user1 = User::factory()->create(['tipe_user' => 'PEDAGANG']);
        $user2 = User::factory()->create(['tipe_user' => 'PEDAGANG']);
        
        Seller::factory()->create(['id_user' => $user1->id]);
        Seller::factory()->create(['id_user' => $user2->id]);

        $response = $this->getJson('/api/sellers');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         '*' => [
                             'id',
                             'id_user',
                             'nama_toko',
                             'deskripsi',
                             'foto_profil',
                             'created_at',
                             'updated_at',
                             'user' => [
                                 'id',
                                 'username',
                                 'email',
                                 'tipe_user',
                                 'no_hp',
                                 'status'
                             ]
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
     * Test creating seller profile
     */
    public function test_create_seller_profile(): void
    {
        $user = User::factory()->create(['tipe_user' => 'PEDAGANG']);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/sellers', [
            'nama_toko' => 'Test Toko',
            'deskripsi' => 'Test description for the seller',
            'alamat_toko' => 'Test address'
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'id',
                         'id_user',
                         'nama_toko',
                         'deskripsi',
                         'foto_profil',
                         'created_at',
                         'updated_at'
                     ]
                 ]);
    }

    /**
     * Test getting seller details
     */
    public function test_get_seller_details(): void
    {
        $user = User::factory()->create(['tipe_user' => 'PEDAGANG']);
        $seller = Seller::factory()->create(['id_user' => $user->id]);

        $response = $this->getJson("/api/sellers/{$seller->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'id',
                         'id_user',
                         'nama_toko',
                         'deskripsi',
                         'foto_profil',
                         'created_at',
                         'updated_at',
                         'user' => [
                             'id',
                             'username',
                             'email',
                             'tipe_user',
                             'no_hp',
                             'status'
                         ]
                     ]
                 ]);
    }

    /**
     * Test updating seller profile
     */
    public function test_update_seller_profile(): void
    {
        $user = User::factory()->create(['tipe_user' => 'PEDAGANG']);
        $seller = Seller::factory()->create(['id_user' => $user->id]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/sellers/{$seller->id}", [
            'nama_toko' => 'Updated Toko',
            'deskripsi' => 'Updated description',
            'alamat_toko' => 'Updated address'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'id',
                         'id_user',
                         'nama_toko',
                         'deskripsi',
                         'foto_profil',
                         'created_at',
                         'updated_at'
                     ]
                 ]);
    }

    /**
     * Test deleting seller profile
     */
    public function test_delete_seller_profile(): void
    {
        $user = User::factory()->create(['tipe_user' => 'PEDAGANG']);
        $seller = Seller::factory()->create(['id_user' => $user->id]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/sellers/{$seller->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message'
                 ]);
    }

    /**
     * Test getting seller products
     */
    public function test_get_seller_products(): void
    {
        $user = User::factory()->create(['tipe_user' => 'PEDAGANG']);
        $seller = Seller::factory()->create(['id_user' => $user->id]);
        
        // Create some products for this seller
        $product = \App\Models\Product::factory()->create(['id_seller' => $seller->id]);

        $response = $this->getJson("/api/sellers/{$seller->id}/products");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data',
                     'pagination'
                 ]);
    }

    /**
     * Test getting seller reviews
     */
    public function test_get_seller_reviews(): void
    {
        $user = User::factory()->create(['tipe_user' => 'PEDAGANG']);
        $seller = Seller::factory()->create(['id_user' => $user->id]);

        $response = $this->getJson("/api/sellers/{$seller->id}/reviews");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data',
                     'pagination'
                 ]);
    }

    /**
     * Test getting seller ratings
     */
    public function test_get_seller_ratings(): void
    {
        $user = User::factory()->create(['tipe_user' => 'PEDAGANG']);
        $seller = Seller::factory()->create(['id_user' => $user->id]);

        $response = $this->getJson("/api/sellers/{$seller->id}/ratings");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data'
                 ]);
    }
}