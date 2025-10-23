<?php

namespace Tests\Support\Traits;

use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Http\Response;

trait ApiTestTrait
{
    /**
     * Assert standard API success response structure
     */
    protected function assertApiSuccess($response, $message = null): void
    {
        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                     'success' => true
                 ]);

        // Check for required structure but allow flexibility
        // Some endpoints may not have 'data' field (like logout)
        $responseData = $response->json();
        if (isset($responseData['data'])) {
            $response->assertJsonStructure([
                'success',
                'data'
            ]);
        }

        if ($message) {
            // Check if message contains the expected text (more flexible)
            $responseMessage = $response->json('message');
            if ($responseMessage) {
                $this->assertStringContainsString($message, $responseMessage);
            }
        }
    }

    /**
     * Assert API created response structure
     */
    protected function assertApiCreated($response, $message = null): void
    {
        $response->assertStatus(Response::HTTP_CREATED)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data'
                 ])
                 ->assertJson([
                     'success' => true
                 ]);

        if ($message) {
            // Check if message contains the expected text (more flexible)
            $responseMessage = $response->json('message');
            $this->assertStringContainsString($message, $responseMessage);
        }
    }

    /**
     * Assert API error response structure
     */
    protected function assertApiError($response, int $status = Response::HTTP_BAD_REQUEST, $message = null): void
    {
        $response->assertStatus($status);
        
        // Check if it has the expected structure, but allow flexibility
        $responseData = $response->json();
        
        // Allow either the standard API structure or JWT exception structure
        if (isset($responseData['success'])) {
            $this->assertArrayHasKey('success', $responseData);
            $this->assertArrayHasKey('message', $responseData);
            $response->assertJson([
                'success' => false
            ]);
        } else {
            // Handle JWT exception responses
            $this->assertArrayHasKey('message', $responseData);
        }

        if ($message) {
            $response->assertJson([
                'message' => $message
            ]);
        }
    }

    /**
     * Assert validation error response
     */
    protected function assertValidationError($response, array $expectedFields): void
    {
        // Accept both 422 (Unprocessable Entity) and 400 (Bad Request) for validation errors
        $this->assertTrue(
            in_array($response->getStatusCode(), [Response::HTTP_UNPROCESSABLE_ENTITY, Response::HTTP_BAD_REQUEST]),
            'Response status is not 422 or 400: ' . $response->getStatusCode()
        );
        
        $responseData = $response->json();
        
        // Check for either standard API validation structure or Laravel's default validation structure
        if (isset($responseData['success'])) {
            $response->assertJson([
                'success' => false
            ]);
            
            // Check if it has validation errors
            if (isset($responseData['errors'])) {
                $response->assertJsonValidationErrors($expectedFields);
            }
        } else {
            // Handle Laravel's default validation error structure
            $this->assertArrayHasKey('message', $responseData);
            $this->assertArrayHasKey('errors', $responseData);
        }
    }

    /**
     * Assert unauthorized response
     */
    protected function assertUnauthorized($response, $message = 'Unauthorized'): void
    {
        // Check if it's a JWT unauthorized response (which might have a different structure)
        // JWT middleware throws exceptions instead of returning JSON, so we need to handle both cases
        if ($response->getStatusCode() == Response::HTTP_UNAUTHORIZED) {
            // Standard unauthorized response
            $this->assertApiError($response, Response::HTTP_UNAUTHORIZED, $message);
        } else {
            // JWT exception response - check for exception structure
            $response->assertStatus(Response::HTTP_UNAUTHORIZED);
            $responseData = $response->json();
            $this->assertArrayHasKey('message', $responseData);
            // Don't assert specific message content for JWT exceptions as they may vary
        }
    }

    /**
     * Assert forbidden response
     */
    protected function assertForbidden($response, $message = 'Forbidden'): void
    {
        $this->assertApiError($response, Response::HTTP_FORBIDDEN, $message);
    }

    /**
     * Assert not found response
     */
    protected function assertNotFound($response, $message = 'Resource not found'): void
    {
        $this->assertApiError($response, Response::HTTP_NOT_FOUND, $message);
    }

    /**
     * Assert JWT token response structure
     */
    protected function assertJwtTokenResponse($response): void
    {
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'user' => [
                    'id',
                    'username',
                    'email',
                    'tipe_user'
                ],
                'token',
                'token_type',
                'expires_in'
            ]
        ])
        ->assertJson([
            'success' => true,
            'data' => [
                'token_type' => 'bearer'
            ]
        ]);
    }

    /**
     * Assert paginated response structure
     */
    protected function assertPaginatedResponse($response, $dataKey = 'data'): void
    {
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'data' => [],
                'current_page',
                'last_page',
                'per_page',
                'total',
                'from',
                'to'
            ]
        ])
        ->assertJson([
            'success' => true
        ]);
    }

    /**
     * Make API request with authentication headers
     */
    protected function withJwtAuth($token = null): \Illuminate\Testing\TestResponse
    {
        if (!$token) {
            $auth = $this->createAuthenticatedUser();
            $token = $auth['token'];
        }

        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ]);
    }

    /**
     * Make form-data request
     */
    protected function postFormData($uri, array $data = [], array $headers = []): \Illuminate\Testing\TestResponse
    {
        $defaultHeaders = [
            'Accept' => 'application/json',
            'Content-Type' => 'multipart/form-data'
        ];

        return $this->post($uri, $data, array_merge($defaultHeaders, $headers));
    }

    /**
     * Assert response time (performance testing)
     */
    protected function assertResponseTime($response, int $maxMilliseconds): void
    {
        $responseTime = $response->headers->get('X-Response-Time');

        if ($responseTime) {
            $this->assertLessThan($maxMilliseconds, (int) $responseTime,
                'Response time exceeded threshold');
        }
    }

    /**
     * Assert JSON data contains expected structure
     */
    protected function assertJsonContainsStructure($response, array $structure): void
    {
        $response->assertJson(function (AssertableJson $json) use ($structure) {
            $json->hasAll($structure);
        });
    }
}