<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Device;

class DeviceControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test listing user devices
     */
    public function test_list_user_devices(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        // Create some devices for the user
        Device::factory()->count(3)->create(['id_user' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/devices');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         '*' => [
                             'id',
                             'id_user',
                             'device_id',
                             'device_name',
                             'device_type',
                             'is_trusted',
                             'last_used_at',
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
     * Test registering new device
     */
    public function test_register_new_device(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/devices', [
            'device_id' => 'test-device-12345',
            'device_name' => 'Test Device',
            'device_type' => 'mobile'
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'id',
                         'id_user',
                         'device_id',
                         'device_name',
                         'device_type',
                         'is_trusted',
                         'last_used_at',
                         'created_at',
                         'updated_at'
                     ]
                 ]);
    }

    /**
     * Test updating device info
     */
    public function test_update_device_info(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;
        
        $device = Device::factory()->create(['id_user' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/devices/{$device->id}", [
            'device_name' => 'Updated Device Name',
            'device_type' => 'tablet'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'id',
                         'id_user',
                         'device_id',
                         'device_name',
                         'device_type',
                         'is_trusted',
                         'last_used_at',
                         'created_at',
                         'updated_at'
                     ]
                 ]);
    }

    /**
     * Test removing device
     */
    public function test_remove_device(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;
        
        $device = Device::factory()->create(['id_user' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/devices/{$device->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message'
                 ]);
    }

    /**
     * Test marking device as trusted
     */
    public function test_mark_device_as_trusted(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;
        
        $device = Device::factory()->create(['id_user' => $user->id, 'is_trusted' => false]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson("/api/devices/{$device->id}/trust", [
            'is_trusted' => true
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'id',
                         'id_user',
                         'device_id',
                         'device_name',
                         'device_type',
                         'is_trusted',
                         'last_used_at',
                         'created_at',
                         'updated_at'
                     ]
                 ]);
        
        // Verify the device is now trusted
        $this->assertTrue($response->json('data.is_trusted'));
    }
}