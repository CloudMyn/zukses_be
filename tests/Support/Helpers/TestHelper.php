<?php

namespace Tests\Support\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class TestHelper
{
    /**
     * Generate random test data
     */
    public static function generateUserData(array $overrides = []): array
    {
        return array_merge([
            'username' => 'test_user_' . Str::random(8),
            'email' => 'test_' . Str::random(8) . '@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'tipe_user' => 'PELANGGAN',
            'nama_depan' => 'Test',
            'nama_belakang' => 'User',
            'contact' => '08123456789',
            'device_id' => 'device_test_' . Str::random(10),
            'device_name' => 'Test Device',
            'operating_system' => 'Test OS'
        ], $overrides);
    }

    /**
     * Generate product test data
     */
    public static function generateProductData(array $overrides = []): array
    {
        return array_merge([
            'nama_produk' => 'Test Product ' . Str::random(8),
            'deskripsi' => 'Test product description',
            'harga' => rand(10000, 1000000),
            'stok' => rand(1, 100),
            'berat' => rand(100, 5000), // grams
            'kategori_id' => 1,
            'sku' => 'SKU_' . Str::random(10),
            'status' => 'aktif'
        ], $overrides);
    }

    /**
     * Generate address test data
     */
    public static function generateAddressData(array $overrides = []): array
    {
        return array_merge([
            'alamat_lengkap' => 'Test Address ' . Str::random(10),
            'province_id' => 1,
            'city_id' => 1,
            'district_id' => 1,
            'postal_code' => '12345',
            'is_primary' => false,
            'latitude' => -6.200000,
            'longitude' => 106.816666
        ], $overrides);
    }

    /**
     * Generate order test data
     */
    public static function generateOrderData(array $overrides = []): array
    {
        return array_merge([
            'total_harga' => rand(50000, 500000),
            'status' => 'pending',
            'payment_method' => 'bank_transfer',
            'shipping_address' => 'Test Shipping Address',
            'notes' => 'Test order notes'
        ], $overrides);
    }

    /**
     * Generate chat message test data
     */
    public static function generateChatMessageData(array $overrides = []): array
    {
        return array_merge([
            'message' => 'Test message ' . Str::random(20),
            'message_type' => 'text',
            'is_read' => false
        ], $overrides);
    }

    /**
     * Generate device test data
     */
    public static function generateDeviceData(array $overrides = []): array
    {
        return array_merge([
            'device_id' => 'device_' . Str::random(15),
            'device_name' => 'Test Device',
            'device_type' => 'mobile',
            'operating_system' => 'Android',
            'app_version' => '1.0.0',
            'is_trusted' => false,
            'last_used_at' => now()
        ], $overrides);
    }

    /**
     * Generate review test data
     */
    public static function generateReviewData(array $overrides = []): array
    {
        return array_merge([
            'rating' => rand(1, 5),
            'review' => 'Test review ' . Str::random(30),
            'is_verified' => false
        ], $overrides);
    }

    /**
     * Generate OTP code
     */
    public static function generateOtp(): string
    {
        return (string) rand(100000, 999999);
    }

    /**
     * Generate JWT test token structure
     */
    public static function generateJwtTokenStructure(): array
    {
        return [
            'token' => 'test_jwt_token_' . Str::random(20),
            'token_type' => 'bearer',
            'expires_in' => 3600
        ];
    }

    /**
     * Create password hash for testing
     */
    public static function hashPassword(string $password): string
    {
        return Hash::make($password);
    }

    /**
     * Generate random phone number
     */
    public static function generatePhoneNumber(): string
    {
        $prefixes = ['0812', '0813', '0821', '0822', '0823', '0852', '0853', '0811'];
        $prefix = $prefixes[array_rand($prefixes)];

        return $prefix . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
    }

    /**
     * Generate random email
     */
    public static function generateEmail(): string
    {
        return 'test_' . Str::random(10) . '@example.com';
    }

    /**
     * Generate test image data
     */
    public static function generateImageData(): string
    {
        return 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////2wBDAf//////////////////////////////////////////////////////////////////////////////////////wAARCABkAJYDASIAAhEBAxEB/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwA/8A8A';
    }

    /**
     * Format currency for testing
     */
    public static function formatCurrency(int $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    /**
     * Generate pagination test data
     */
    public static function generatePaginationData(int $total = 50, int $perPage = 10, int $currentPage = 1): array
    {
        $lastPage = ceil($total / $perPage);

        return [
            'data' => [],
            'current_page' => $currentPage,
            'last_page' => $lastPage,
            'per_page' => $perPage,
            'total' => $total,
            'from' => ($currentPage - 1) * $perPage + 1,
            'to' => min($currentPage * $perPage, $total)
        ];
    }

    /**
     * Clean string for testing (remove special characters)
     */
    public static function cleanString(string $string): string
    {
        return preg_replace('/[^A-Za-z0-9 ]/', '', $string);
    }

    /**
     * Generate timestamp for testing
     */
    public static function generateTimestamp(string $format = 'Y-m-d H:i:s'): string
    {
        return now()->format($format);
    }
}