<?php

namespace App\Services\Midtrans;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use App\Services\Midtrans\MidtransHttpClient;
use App\Services\Midtrans\MidtransValidator;
use App\Services\Midtrans\MidtransSecurity;

/**
 * MidtransService
 *
 * Service utama untuk integrasi dengan Midtrans Payment Gateway
 * Memberikan interface yang aman dan reliable untuk melakukan transaksi pembayaran
 *
 * @author Zukses Development Team
 * @version 1.0.0
 */
class MidtransService
{
    /**
     * HTTP Client untuk komunikasi dengan Midtrans API
     */
    protected MidtransHttpClient $httpClient;

    /**
     * Validator untuk data transaksi
     */
    protected MidtransValidator $validator;

    /**
     * Security service untuk enkripsi dan validasi
     */
    protected MidtransSecurity $security;

    /**
     * Konfigurasi Midtrans
     */
    protected array $config;

    /**
     * Constructor
     *
     * @param MidtransHttpClient $httpClient
     * @param MidtransValidator $validator
     * @param MidtransSecurity $security
     */
    public function __construct(
        MidtransHttpClient $httpClient,
        MidtransValidator $validator,
        MidtransSecurity $security
    ) {
        $this->httpClient = $httpClient;
        $this->validator = $validator;
        $this->security = $security;
        $this->config = Config::get('midtrans');

        // Set Midtrans configuration
        $this->configureMidtrans();
    }

    /**
     * Konfigurasi Midtrans SDK
     */
    private function configureMidtrans(): void
    {
        try {
            // Set server key
            \Midtrans\Config::$serverKey = $this->config['server_key'];

            // Set client key
            \Midtrans\Config::$clientKey = $this->config['client_key'];

            // Set environment (sandbox/production)
            \Midtrans\Config::$isProduction = $this->config['environment'] === 'production';

            // Set 3DS secure
            \Midtrans\Config::$is3ds = true;

            // Set Sanitized (for security)
            \Midtrans\Config::$isSanitized = true;

            // Set append notif URL
            \Midtrans\Config::$appendNotifUrl = $this->config['webhook']['payment_notification'];

            // Log konfigurasi berhasil
            Log::info('MidtransService: Konfigurasi berhasil', [
                'environment' => $this->config['environment'],
                'is_production' => \Midtrans\Config::$isProduction
            ]);
        } catch (Exception $e) {
            Log::error('MidtransService: Gagal konfigurasi Midtrans', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new Exception('Gagal mengkonfigurasi Midtrans: ' . $e->getMessage());
        }
    }

    /**
     * Membuat SNAP token untuk transaksi
     *
     * @param array $transactionData Data transaksi
     * @return array
     * @throws Exception
     */
    public function createSnapToken(array $transactionData): array
    {
        $startTime = microtime(true);

        try {
            // Log request
            Log::info('MidtransService: Membuat SNAP token', [
                'transaction_id' => $transactionData['transaction_id'] ?? 'unknown',
                'amount' => $transactionData['gross_amount'] ?? 0
            ]);

            // Validasi data transaksi
            $this->validator->validateTransactionData($transactionData);

            // Sanitize data untuk keamanan
            $sanitizedData = $this->security->sanitizeTransactionData($transactionData);

            // Generate request ID untuk idempotency
            $requestId = $this->security->generateRequestId();
            $sanitizedData['request_id'] = $requestId;

            // Cek cache untuk prevent duplicate
            $cacheKey = "snap_token_{$sanitizedData['transaction_id']}";
            if (Cache::has($cacheKey)) {
                Log::info('MidtransService: SNAP token diambil dari cache', [
                    'transaction_id' => $sanitizedData['transaction_id']
                ]);
                return Cache::get($cacheKey);
            }

            // Create SNAP transaction
            $snapResponse = \Midtrans\Snap::createTransaction($sanitizedData);

            // Extract data dari response
            $result = [
                'token' => $snapResponse->token,
                'redirect_url' => $snapResponse->redirect_url,
                'transaction_id' => $sanitizedData['transaction_id'],
                'request_id' => $requestId,
                'expiry_time' => $sanitizedData['expiry_time'] ?? null,
                'created_at' => now()->toISOString(),
                'execution_time_ms' => round((microtime(true) - $startTime) * 1000)
            ];

            // Cache response untuk beberapa menit
            Cache::put($cacheKey, $result, $this->config['cache']['snap_token_ttl']);

            // Log success
            Log::info('MidtransService: SNAP token berhasil dibuat', [
                'transaction_id' => $sanitizedData['transaction_id'],
                'execution_time_ms' => $result['execution_time_ms']
            ]);

            return $result;

        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000);

            // Log error
            Log::error('MidtransService: Gagal membuat SNAP token', [
                'transaction_id' => $transactionData['transaction_id'] ?? 'unknown',
                'error' => $e->getMessage(),
                'execution_time_ms' => $executionTime,
                'trace' => $e->getTraceAsString()
            ]);

            throw new Exception('Gagal membuat token pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Mendapatkan status transaksi dari Midtrans
     *
     * @param string $transactionId ID transaksi Midtrans
     * @param string $orderId ID order
     * @return array
     * @throws Exception
     */
    public function getTransactionStatus(string $transactionId, string $orderId): array
    {
        $startTime = microtime(true);

        try {
            Log::info('MidtransService: Mendapatkan status transaksi', [
                'transaction_id' => $transactionId,
                'order_id' => $orderId
            ]);

            // Cek cache
            $cacheKey = "status_{$transactionId}";
            if (Cache::has($cacheKey)) {
                Log::info('MidtransService: Status transaksi diambil dari cache', [
                    'transaction_id' => $transactionId
                ]);
                return Cache::get($cacheKey);
            }

            // Get status dari Midtrans
            $status = \Midtrans\Transaction::status($orderId);

            // Format response
            $result = [
                'transaction_id' => $transactionId,
                'order_id' => $orderId,
                'status_code' => $status->status_code,
                'status_message' => $status->status_message,
                'transaction_status' => $status->transaction_status,
                'payment_type' => $status->payment_type ?? null,
                'gross_amount' => $status->gross_amount ?? null,
                'transaction_time' => $status->transaction_time ?? null,
                'settlement_time' => $status->settlement_time ?? null,
                'expiry_time' => $status->expiry_time ?? null,
                'fraud_status' => $status->fraud_status ?? null,
                'signature_key' => $status->signature_key ?? null,
                'payment_code' => $status->payment_code ?? null,
                'va_number' => $status->va_number ?? null,
                'bank' => $status->bank ?? null,
                'bill_key' => $status->bill_key ?? null,
                'biller_code' => $status->biller_code ?? null,
                'execution_time_ms' => round((microtime(true) - $startTime) * 1000),
                'retrieved_at' => now()->toISOString()
            ];

            // Cache response
            Cache::put($cacheKey, $result, $this->config['cache']['payment_status_ttl']);

            Log::info('MidtransService: Status transaksi berhasil diambil', [
                'transaction_id' => $transactionId,
                'status' => $result['transaction_status'],
                'execution_time_ms' => $result['execution_time_ms']
            ]);

            return $result;

        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000);

            Log::error('MidtransService: Gagal mendapatkan status transaksi', [
                'transaction_id' => $transactionId,
                'order_id' => $orderId,
                'error' => $e->getMessage(),
                'execution_time_ms' => $executionTime,
                'trace' => $e->getTraceAsString()
            ]);

            throw new Exception('Gagal mendapatkan status transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Membatalkan transaksi
     *
     * @param string $orderId ID order
     * @return array
     * @throws Exception
     */
    public function cancelTransaction(string $orderId): array
    {
        $startTime = microtime(true);

        try {
            Log::info('MidtransService: Membatalkan transaksi', [
                'order_id' => $orderId
            ]);

            // Cancel transaksi
            $cancelResponse = \Midtrans\Transaction::cancel($orderId);

            $result = [
                'order_id' => $orderId,
                'status_code' => $cancelResponse->status_code,
                'status_message' => $cancelResponse->status_message,
                'transaction_status' => $cancelResponse->transaction_status,
                'cancelled_at' => now()->toISOString(),
                'execution_time_ms' => round((microtime(true) - $startTime) * 1000)
            ];

            Log::info('MidtransService: Transaksi berhasil dibatalkan', [
                'order_id' => $orderId,
                'status' => $result['transaction_status']
            ]);

            return $result;

        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000);

            Log::error('MidtransService: Gagal membatalkan transaksi', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
                'execution_time_ms' => $executionTime,
                'trace' => $e->getTraceAsString()
            ]);

            throw new Exception('Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Melakukan refund
     *
     * @param string $orderId ID order
     * @param int $amount Jumlah refund
     * @param string $reason Alasan refund
     * @return array
     * @throws Exception
     */
    public function refundTransaction(string $orderId, int $amount, string $reason = ''): array
    {
        $startTime = microtime(true);

        try {
            Log::info('MidtransService: Melakukan refund', [
                'order_id' => $orderId,
                'amount' => $amount,
                'reason' => $reason
            ]);

            $refundParams = [
                'refund_key' => $this->security->generateRequestId(),
                'amount' => $amount,
                'reason' => $reason
            ];

            // Proses refund
            $refundResponse = \Midtrans\Transaction::refund($orderId, $refundParams);

            $result = [
                'order_id' => $orderId,
                'refund_key' => $refundParams['refund_key'],
                'amount' => $amount,
                'reason' => $reason,
                'status_code' => $refundResponse->status_code,
                'status_message' => $refundResponse->status_message,
                'refund_status' => $refundResponse->refund_status ?? null,
                'refunded_at' => now()->toISOString(),
                'execution_time_ms' => round((microtime(true) - $startTime) * 1000)
            ];

            Log::info('MidtransService: Refund berhasil diproses', [
                'order_id' => $orderId,
                'amount' => $amount,
                'status' => $result['refund_status']
            ]);

            return $result;

        } catch (Exception $e) {
            $executionTime = round((microtime(true) - $startTime) * 1000);

            Log::error('MidtransService: Gagal melakukan refund', [
                'order_id' => $orderId,
                'amount' => $amount,
                'error' => $e->getMessage(),
                'execution_time_ms' => $executionTime,
                'trace' => $e->getTraceAsString()
            ]);

            throw new Exception('Gagal melakukan refund: ' . $e->getMessage());
        }
    }

    /**
     * Validasi notifikasi dari Midtrans
     *
     * @param array $notificationData Data notifikasi
     * @return bool
     */
    public function validateNotification(array $notificationData): bool
    {
        try {
            return $this->security->validateSignatureKey($notificationData);
        } catch (Exception $e) {
            Log::error('MidtransService: Validasi notifikasi gagal', [
                'error' => $e->getMessage(),
                'notification_data' => $this->security->sanitizeLogData($notificationData)
            ]);
            return false;
        }
    }

    /**
     * Mendapatkan konfigurasi payment yang diizinkan
     *
     * @return array
     */
    public function getEnabledPayments(): array
    {
        return $this->config['transaction']['enabled_payments'] ?? [];
    }

    /**
     * Mendapatkan konfigurasi batas jumlah transaksi
     *
     * @return array
     */
    public function getTransactionLimits(): array
    {
        return [
            'min_amount' => $this->config['transaction']['min_amount'] ?? 1000,
            'max_amount' => $this->config['transaction']['max_amount'] ?? 100000000
        ];
    }

    /**
     * Cek apakah environment sedang dalam mode development
     *
     * @return bool
     */
    public function isSandboxMode(): bool
    {
        return $this->config['environment'] !== 'production';
    }
}