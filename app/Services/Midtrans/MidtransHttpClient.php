<?php

namespace App\Services\Midtrans;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;

/**
 * MidtransHttpClient
 *
 * HTTP Client khusus untuk komunikasi dengan Midtrans API
 * Memberikan keamanan, retry mechanism, dan proper error handling
 *
 * @author Zukses Development Team
 * @version 1.0.0
 */
class MidtransHttpClient
{
    /**
     * Base URL untuk Midtrans API
     */
    private string $baseUrl;

    /**
     * Server key untuk autentikasi
     */
    private string $serverKey;

    /**
     * Konfigurasi timeout
     */
    private int $timeout;

    /**
     * Jumlah maksimal retry
     */
    private int $maxRetries;

    /**
     * Delay antar retry dalam detik
     */
    private int $retryDelay;

    /**
     * Constructor
     */
    public function __construct()
    {
        $config = config('midtrans');

        $this->baseUrl = $config['urls'][$config['environment']]['api_url'];
        $this->serverKey = $config['server_key'];
        $this->timeout = $config['error_handling']['max_retries'] ?? 30;
        $this->maxRetries = $config['error_handling']['max_retries'] ?? 3;
        $this->retryDelay = $config['error_handling']['retry_delay'] ?? 1;
    }

    /**
     * Melakukan HTTP request ke Midtrans API dengan retry mechanism
     *
     * @param string $method HTTP method
     * @param string $endpoint Endpoint URL
     * @param array $data Request data
     * @param array $headers Additional headers
     * @return array
     * @throws Exception
     */
    public function request(string $method, string $endpoint, array $data = [], array $headers = []): array
    {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');
        $attempt = 0;
        $lastException = null;

        while ($attempt < $this->maxRetries) {
            $attempt++;

            try {
                $startTime = microtime(true);

                Log::info('MidtransHttpClient: Melakukan request', [
                    'method' => $method,
                    'url' => $url,
                    'attempt' => $attempt,
                    'max_attempts' => $this->maxRetries
                ]);

                // Prepare headers
                $requestHeaders = array_merge([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode($this->serverKey . ':'),
                    'User-Agent' => 'Zukses-Midtrans/1.0.0',
                    'X-Request-ID' => $this->generateRequestId()
                ], $headers);

                // Create HTTP client with timeout
                $httpClient = Http::timeout($this->timeout)
                    ->withHeaders($requestHeaders)
                    ->withOptions([
                        'verify' => true, // SSL verification
                        'allow_redirects' => true,
                        'connect_timeout' => 10,
                        'read_timeout' => 30
                    ]);

                // Execute request
                $response = $this->executeRequest($httpClient, $method, $url, $data);

                $executionTime = round((microtime(true) - $startTime) * 1000);

                // Log success
                Log::info('MidtransHttpClient: Request berhasil', [
                    'method' => $method,
                    'url' => $url,
                    'status_code' => $response->status(),
                    'attempt' => $attempt,
                    'execution_time_ms' => $executionTime
                ]);

                // Return response data
                return [
                    'data' => $response->json(),
                    'status_code' => $response->status(),
                    'headers' => $response->headers(),
                    'execution_time_ms' => $executionTime,
                    'attempt' => $attempt
                ];

            } catch (RequestException $e) {
                $lastException = $e;
                $executionTime = round((microtime(true) - $startTime) * 1000);

                Log::warning('MidtransHttpClient: Request gagal', [
                    'method' => $method,
                    'url' => $url,
                    'attempt' => $attempt,
                    'status_code' => $e->response?->status(),
                    'error' => $e->getMessage(),
                    'execution_time_ms' => $executionTime,
                    'will_retry' => $attempt < $this->maxRetries
                ]);

                // Check if we should retry
                if (!$this->shouldRetry($e) || $attempt >= $this->maxRetries) {
                    break;
                }

                // Wait before retry
                $this->waitBeforeRetry($attempt);

            } catch (Exception $e) {
                $lastException = $e;
                $executionTime = round((microtime(true) - $startTime) * 1000);

                Log::error('MidtransHttpClient: Request error', [
                    'method' => $method,
                    'url' => $url,
                    'attempt' => $attempt,
                    'error' => $e->getMessage(),
                    'execution_time_ms' => $executionTime,
                    'will_retry' => $attempt < $this->maxRetries
                ]);

                if ($attempt >= $this->maxRetries) {
                    break;
                }

                $this->waitBeforeRetry($attempt);
            }
        }

        // All attempts failed, throw the last exception
        $this->handleFailedRequest($method, $url, $lastException);
    }

    /**
     * Execute HTTP request based on method
     *
     * @param \Illuminate\Http\Client\PendingRequest $client
     * @param string $method
     * @param string $url
     * @param array $data
     * @return Response
     * @throws RequestException
     */
    private function executeRequest($client, string $method, string $url, array $data): Response
    {
        switch (strtoupper($method)) {
            case 'GET':
                return $client->get($url, $data);

            case 'POST':
                return $client->post($url, $data);

            case 'PUT':
                return $client->put($url, $data);

            case 'PATCH':
                return $client->patch($url, $data);

            case 'DELETE':
                return $client->delete($url, $data);

            default:
                throw new Exception("Unsupported HTTP method: {$method}");
        }
    }

    /**
     * Check if request should be retried
     *
     * @param RequestException $e
     * @return bool
     */
    private function shouldRetry(RequestException $e): bool
    {
        $statusCode = $e->response?->status();

        // Retry on these status codes
        $retryableStatusCodes = [
            429, // Too Many Requests
            500, // Internal Server Error
            502, // Bad Gateway
            503, // Service Unavailable
            504, // Gateway Timeout
        ];

        // Retry on connection errors
        $connectionErrors = [
            'timeout',
            'connection refused',
            'connection timed out',
            'could not resolve host',
        ];

        // Check status code
        if ($statusCode && in_array($statusCode, $retryableStatusCodes)) {
            return true;
        }

        // Check connection error messages
        $errorMessage = strtolower($e->getMessage());
        foreach ($connectionErrors as $error) {
            if (strpos($errorMessage, $error) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Wait before retry with exponential backoff
     *
     * @param int $attempt
     */
    private function waitBeforeRetry(int $attempt): void
    {
        $delay = $this->retryDelay * pow(2, $attempt - 1); // Exponential backoff
        $delay = min($delay, 30); // Maximum 30 seconds

        Log::info('MidtransHttpClient: Menunggu sebelum retry', [
            'delay_seconds' => $delay,
            'attempt' => $attempt
        ]);

        usleep($delay * 1000000); // Convert to microseconds
    }

    /**
     * Handle failed request after all retries
     *
     * @param string $method
     * @param string $url
     * @param Exception $exception
     * @throws Exception
     */
    private function handleFailedRequest(string $method, string $url, Exception $exception): void
    {
        Log::error('MidtransHttpClient: Semua percobaan gagal', [
            'method' => $method,
            'url' => $url,
            'error' => $exception->getMessage(),
            'max_attempts' => $this->maxRetries
        ]);

        if ($exception instanceof RequestException && $exception->response) {
            $statusCode = $exception->response->status();
            $responseBody = $exception->response->json() ?? $exception->response->body();

            throw new Exception("Midtrans API Error: {$statusCode} - " .
                ($responseBody['error_message'] ?? $exception->getMessage()));
        }

        throw new Exception("Koneksi ke Midtrans gagal: " . $exception->getMessage());
    }

    /**
     * Generate unique request ID for tracking
     *
     * @return string
     */
    private function generateRequestId(): string
    {
        return uniqid('midtrans_', true) . '_' . time();
    }

    /**
     * Test connection to Midtrans API
     *
     * @return bool
     */
    public function testConnection(): bool
    {
        try {
            $response = $this->request('GET', '/v2/status', []);

            return $response['status_code'] === 200;

        } catch (Exception $e) {
            Log::error('MidtransHttpClient: Koneksi test gagal', [
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Get API server information
     *
     * @return array
     */
    public function getServerInfo(): array
    {
        return [
            'base_url' => $this->baseUrl,
            'timeout' => $this->timeout,
            'max_retries' => $this->maxRetries,
            'retry_delay' => $this->retryDelay,
            'is_sandbox' => strpos($this->baseUrl, 'sandbox') !== false
        ];
    }
}