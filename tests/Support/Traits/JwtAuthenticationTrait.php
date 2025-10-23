<?php

namespace Tests\Support\Traits;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

trait JwtAuthenticationTrait
{
    /**
     * Create authenticated user with JWT token
     */
    protected function createAuthenticatedUser(array $userData = []): array
    {
        $user = User::factory()->create($userData);
        $token = JWTAuth::fromUser($user);

        return [
            'user' => $user,
            'token' => $token,
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json'
            ]
        ];
    }

    /**
     * Create user with specific role
     */
    protected function createUserWithRole(string $role, array $userData = []): array
    {
        return $this->createAuthenticatedUser(array_merge($userData, [
            'tipe_user' => $role
        ]));
    }

    /**
     * Get JWT headers for existing user
     */
    protected function getJwtHeaders(User $user): array
    {
        $token = JWTAuth::fromUser($user);

        return [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ];
    }

    /**
     * Create multiple authenticated users with different roles
     */
    protected function createUsersWithRoles(): array
    {
        $customer = $this->createUserWithRole('PELANGGAN');
        $brandOwner = $this->createUserWithRole('BRAND_OWNER');
        $reseller = $this->createUserWithRole('RESELLER');
        $admin = $this->createUserWithRole('ADMIN');

        return compact('customer', 'brandOwner', 'reseller', 'admin');
    }

    /**
     * Refresh JWT token
     */
    protected function refreshToken(string $token): string
    {
        return JWTAuth::setToken($token)->refresh();
    }

    /**
     * Invalidate JWT token
     */
    protected function invalidateToken(string $token): void
    {
        JWTAuth::setToken($token)->invalidate();
    }

    /**
     * Get user from JWT token
     */
    protected function getUserFromToken(string $token): ?User
    {
        return JWTAuth::setToken($token)->user();
    }
}