<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\Traits\JwtAuthenticationTrait;
use Tests\Support\Traits\ApiTestTrait;
use Tests\Support\Traits\DatabaseSetupTrait;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;
    use JwtAuthenticationTrait;
    use ApiTestTrait;
    // Remove DatabaseSetupTrait because RefreshDatabase already handles database operations

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create test environment configuration
        $this->createTestEnvironment();
    }

    /**
     * Clean up the testing environment before the next test.
     */
    protected function tearDown(): void
    {
        // Clean up any JWT tokens
        $this->artisan('jwt:secret', ['--force' => true]);

        parent::tearDown();
    }

    /**
     * Assert that the test database has the expected structure
     */
    protected function assertDatabaseStructure(): void
    {
        $requiredTables = [
            'users',
            'user_profiles',
            'products',
            'categories',
            'orders',
            'chat_conversations',
            'chat_messages'
        ];

        foreach ($requiredTables as $table) {
            $this->assertTrue(
                $this->getConnection()->getSchemaBuilder()->hasTable($table),
                "Table {$table} should exist in test database"
            );
        }
    }

    /**
     * Create test environment configuration
     */
    protected function createTestEnvironment(): void
    {
        config(['jwt.secret' => env('JWT_SECRET', 'test_jwt_secret_for_testing_only')]);
        config(['jwt.ttl' => (int) env('JWT_TTL', 60)]);
        config(['jwt.refresh_ttl' => (int) env('JWT_REFRESH_TTL', 20160)]);
        config(['jwt.algo' => env('JWT_ALGORITHM', 'HS256')]);
    }

    /**
     * Assert API response structure for success
     */
    protected function assertSuccessResponse($response, $message = null): void
    {
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true
                 ]);

        if ($message) {
            $response->assertJson([
                'message' => $message
            ]);
        }
    }

    /**
     * Assert API response structure for created resource
     */
    protected function assertCreatedResponse($response, $message = null): void
    {
        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true
                 ]);

        if ($message) {
            $response->assertJson([
                'message' => $message
            ]);
        }
    }

    /**
     * Assert API response structure for errors
     */
    protected function assertErrorResponse($response, int $status = 400, $message = null): void
    {
        $response->assertStatus($status)
                 ->assertJson([
                     'success' => false
                 ]);

        if ($message) {
            $response->assertJson([
                'message' => $message
            ]);
        }
    }
}
