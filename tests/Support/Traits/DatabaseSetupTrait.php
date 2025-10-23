<?php

namespace Tests\Support\Traits;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;

trait DatabaseSetupTrait
{
    use RefreshDatabase;

    /**
     * Setup database for testing
     */
    protected function setUpDatabase(): void
    {
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed', ['--class' => 'DatabaseSeeder']);
    }

    /**
     * Create test data for specific features
     */
    protected function createTestData(string $feature): void
    {
        switch ($feature) {
            case 'auth':
                $this->createAuthTestData();
                break;
            case 'products':
                $this->createProductTestData();
                break;
            case 'orders':
                $this->createOrderTestData();
                break;
            case 'chat':
                $this->createChatTestData();
                break;
            default:
                $this->createBasicTestData();
        }
    }

    /**
     * Create basic test data
     */
    protected function createBasicTestData(): void
    {
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\BasicTestSeeder']);
    }

    /**
     * Create authentication test data
     */
    protected function createAuthTestData(): void
    {
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\AuthTestSeeder']);
    }

    /**
     * Create product test data
     */
    protected function createProductTestData(): void
    {
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\ProductTestSeeder']);
    }

    /**
     * Create order test data
     */
    protected function createOrderTestData(): void
    {
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\OrderTestSeeder']);
    }

    /**
     * Create chat test data
     */
    protected function createChatTestData(): void
    {
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\Chat\\ChatTestSeeder']);
    }

    /**
     * Get database connection for testing
     */
    protected function getTestConnection(): \Illuminate\Database\Connection
    {
        return DB::connection('mysql');
    }

    /**
     * Count table records
     */
    protected function countTableRecords(string $table): int
    {
        return DB::table($table)->count();
    }

    /**
     * Assert table has specific number of records
     */
    protected function assertTableCount(string $table, int $expected): void
    {
        $actual = $this->countTableRecords($table);
        $this->assertEquals($expected, $actual,
            "Table {$table} expected {$expected} records, got {$actual}");
    }

    /**
     * Assert table has at least minimum records
     */
    protected function assertTableMinCount(string $table, int $minimum): void
    {
        $actual = $this->countTableRecords($table);
        $this->assertGreaterThanOrEqual($minimum, $actual,
            "Table {$table} expected at least {$minimum} records, got {$actual}");
    }

    /**
     * Truncate specific table
     */
    protected function truncateTable(string $table): void
    {
        DB::table($table)->truncate();
    }

    /**
     * Insert test record
     */
    protected function insertTestRecord(string $table, array $data): int
    {
        return DB::table($table)->insertGetId($data);
    }

    /**
     * Get test record
     */
    protected function getTestRecord(string $table, mixed $id): ?object
    {
        return DB::table($table)->find($id);
    }

    /**
     * Begin database transaction
     */
    protected function beginDatabaseTransaction(): void
    {
        DB::beginTransaction();
    }

    /**
     * Rollback database transaction
     */
    protected function rollbackDatabaseTransaction(): void
    {
        DB::rollBack();
    }

    /**
     * Commit database transaction
     */
    protected function commitDatabaseTransaction(): void
    {
        DB::commit();
    }
}