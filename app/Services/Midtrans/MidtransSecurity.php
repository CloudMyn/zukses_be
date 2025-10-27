<?php

namespace App\Services\Midtrans;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

/**
 * MidtransSecurity
 *
 * Service untuk menghandle keamanan transaksi Midtrans
 * Melakukan enkripsi, validasi signature, dan sanitasi data
 *
 * @author Zukses Development Team
 * @version 1.0.0
 */
class MidtransSecurity
{
    /**
     * Server key dari Midtrans
     */
    private string $serverKey;

    /**
     * Environment (sandbox/production)
     */
    private string $environment;

    /**
     * Constructor
     */
    public function __construct()
    {
        $config = config('midtrans');
        $this->serverKey = $config['server_key'];
        $this->environment = $config['environment'];
    }

    /**
     * Validasi signature key dari notifikasi Midtrans
     *
     * @param array $notificationData Data notifikasi dari Midtrans
     * @return bool
     * @throws Exception
     */
    public function validateSignatureKey(array $notificationData): bool
    {
        try {
            Log::info('MidtransSecurity: Memvalidasi signature key', [
                'order_id' => $notificationData['order_id'] ?? 'unknown',
                'status_code' => $notificationData['status_code'] ?? 'unknown'
            ]);

            // Required fields untuk validasi
            $requiredFields = [
                'order_id',
                'status_code',
                'gross_amount',
                'signature_key'
            ];

            // Cek required fields
            foreach ($requiredFields as $field) {
                if (!isset($notificationData[$field])) {
                    Log::error('MidtransSecurity: Field required tidak ada', [
                        'missing_field' => $field,
                        'notification_data' => $this->sanitizeLogData($notificationData)
                    ]);
                    return false;
                }
            }

            // Format order_id, status_code, dan gross_amount
            $orderId = (string) $notificationData['order_id'];
            $statusCode = (string) $notificationData['status_code'];
            $grossAmount = (string) $notificationData['gross_amount'];

            // Generate signature key yang diharapkan
            $expectedSignature = $this->generateSignatureKey($orderId, $statusCode, $grossAmount);
            $receivedSignature = $notificationData['signature_key'];

            // Validasi signature
            $isValid = hash_equals($expectedSignature, $receivedSignature);

            if ($isValid) {
                Log::info('MidtransSecurity: Signature key valid', [
                    'order_id' => $orderId
                ]);
            } else {
                Log::warning('MidtransSecurity: Signature key tidak valid', [
                    'order_id' => $orderId,
                    'expected' => substr($expectedSignature, 0, 20) . '...',
                    'received' => substr($receivedSignature, 0, 20) . '...'
                ]);
            }

            return $isValid;

        } catch (Exception $e) {
            Log::error('MidtransSecurity: Error validasi signature key', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'notification_data' => $this->sanitizeLogData($notificationData)
            ]);
            return false;
        }
    }

    /**
     * Generate signature key
     *
     * @param string $orderId
     * @param string $statusCode
     * @param string $grossAmount
     * @return string
     */
    public function generateSignatureKey(string $orderId, string $statusCode, string $grossAmount): string
    {
        $input = $orderId . $statusCode . $grossAmount . $this->serverKey;
        return hash('sha512', $input);
    }

    /**
     * Generate unique request ID untuk idempotency
     *
     * @return string
     */
    public function generateRequestId(): string
    {
        return uniqid('req_', true) . '_' . time() . '_' . Str::random(8);
    }

    /**
     * Generate transaction ID yang aman
     *
     * @param string $userId
     * @param string $orderId
     * @return string
     */
    public function generateTransactionId(string $userId, string $orderId): string
    {
        $timestamp = time();
        $random = Str::random(8);
        $hash = substr(hash('sha256', $userId . $orderId . $timestamp . $random), 0, 16);

        return "TXN{$timestamp}{$hash}";
    }

    /**
     * Sanitasi data transaksi untuk keamanan
     *
     * @param array $data
     * @return array
     */
    public function sanitizeTransactionData(array $data): array
    {
        try {
            // Deep copy data untuk menghindari reference issues
            $sanitized = json_decode(json_encode($data), true);

            // Sanitasi string fields
            $stringFields = [
                'transaction_id', 'order_id',
                'customer_details.first_name', 'customer_details.last_name',
                'customer_details.email', 'customer_details.phone'
            ];

            foreach ($stringFields as $field) {
                $value = $this->getNestedValue($sanitized, $field);
                if ($value && is_string($value)) {
                    $this->setNestedValue($sanitized, $field, $this->sanitizeString($value));
                }
            }

            // Sanitasi item details
            if (isset($sanitized['item_details']) && is_array($sanitized['item_details'])) {
                foreach ($sanitized['item_details'] as &$item) {
                    if (isset($item['name']) && is_string($item['name'])) {
                        $item['name'] = $this->sanitizeString($item['name']);
                    }
                    if (isset($item['category']) && is_string($item['category'])) {
                        $item['category'] = $this->sanitizeString($item['category']);
                    }
                    if (isset($item['merchant_name']) && is_string($item['merchant_name'])) {
                        $item['merchant_name'] = $this->sanitizeString($item['merchant_name']);
                    }
                }
            }

            // Remove potentially harmful HTML/JS
            $sanitized = $this->removeHarmfulContent($sanitized);

            Log::info('MidtransSecurity: Data transaksi berhasil disanitasi', [
                'transaction_id' => $sanitized['transaction_id'] ?? 'unknown'
            ]);

            return $sanitized;

        } catch (Exception $e) {
            Log::error('MidtransSecurity: Error sanitasi data transaksi', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new Exception('Gagal sanitasi data transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Sanitasi string untuk keamanan
     *
     * @param string $input
     * @return string
     */
    private function sanitizeString(string $input): string
    {
        // Trim whitespace
        $input = trim($input);

        // Remove HTML tags
        $input = strip_tags($input);

        // Convert special characters to HTML entities
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

        // Remove null bytes
        $input = str_replace("\0", '', $input);

        // Remove control characters except newlines and tabs
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);

        // Limit length
        if (strlen($input) > 255) {
            $input = substr($input, 0, 255);
        }

        return $input;
    }

    /**
     * Remove harmful content dari array
     *
     * @param array $data
     * @return array
     */
    private function removeHarmfulContent(array $data): array
    {
        $harmfulPatterns = [
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
            '/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/mi',
            '/<object\b[^<]*(?:(?!<\/object>)<[^<]*)*<\/object>/mi',
            '/<embed\b[^<]*(?:(?!<\/embed>)<[^<]*)*<\/embed>/mi',
            '/javascript:/i',
            '/vbscript:/i',
            '/on\w+\s*=/i',
            '/expression\s*\(/i',
        ];

        array_walk_recursive($data, function (&$value) use ($harmfulPatterns) {
            if (is_string($value)) {
                foreach ($harmfulPatterns as $pattern) {
                    $value = preg_replace($pattern, '', $value);
                }
            }
        });

        return $data;
    }

    /**
     * Encrypt sensitive data
     *
     * @param string $data
     * @return string
     */
    public function encryptSensitiveData(string $data): string
    {
        try {
            return Crypt::encrypt($data);
        } catch (Exception $e) {
            Log::error('MidtransSecurity: Gagal enkripsi data', [
                'error' => $e->getMessage()
            ]);
            throw new Exception('Gagal enkripsi data: ' . $e->getMessage());
        }
    }

    /**
     * Decrypt sensitive data
     *
     * @param string $encryptedData
     * @return string
     */
    public function decryptSensitiveData(string $encryptedData): string
    {
        try {
            return Crypt::decrypt($encryptedData);
        } catch (Exception $e) {
            Log::error('MidtransSecurity: Gagal dekripsi data', [
                'error' => $e->getMessage()
            ]);
            throw new Exception('Gagal dekripsi data: ' . $e->getMessage());
        }
    }

    /**
     * Validate IP address untuk webhook
     *
     * @param string $ipAddress
     * @return bool
     */
    public function validateWebhookIp(string $ipAddress): bool
    {
        // Daftar IP Midtrans yang valid
        $midtransIps = [
            // Production IPs
            '103.20.91.19',
            '103.20.91.20',
            '103.20.91.21',
            '103.20.91.22',
            '103.20.91.23',
            '103.20.91.24',
            '103.20.91.25',
            '103.20.91.26',
            '103.20.91.27',
            '103.20.91.28',
            '103.20.91.29',
            '103.20.91.30',
            '103.20.91.31',
            '103.20.91.32',
            '103.20.91.33',
            '103.20.91.34',
            '103.20.91.35',
            '103.20.91.36',
            '103.20.91.37',
            '103.20.91.38',
            '103.20.91.39',
            '103.20.91.40',
            '103.20.91.41',
            '103.20.91.42',
            '103.20.91.43',
            '103.20.91.44',
            '103.20.91.45',
            '103.20.91.46',
            '103.20.91.47',
            '103.20.91.48',
            '103.20.91.49',
            '103.20.91.50',
            // Sandbox IPs
            '182.253.225.13',
            '182.253.225.14',
            '182.253.225.15',
            '182.253.225.16',
            '182.253.225.17',
            '182.253.225.18',
            '182.253.225.19',
            '182.253.225.20',
            '182.253.225.21',
            '182.253.225.22',
            '182.253.225.23',
            '182.253.225.24',
            '182.253.225.25',
            '182.253.225.26',
            '182.253.225.27',
            '182.253.225.28',
            '182.253.225.29',
            '182.253.225.30',
        ];

        return in_array($ipAddress, $midtransIps);
    }

    /**
     * Cek apakah environment adalah sandbox
     *
     * @return bool
     */
    public function isSandboxEnvironment(): bool
    {
        return $this->environment !== 'production';
    }

    /**
     * Sanitasi data untuk logging
     *
     * @param array $data
     * @return array
     */
    public function sanitizeLogData(array $data): array
    {
        $sensitiveKeys = [
            'signature_key',
            'token_id',
            'save_token',
            'card_number',
            'cvv',
            'password',
            'server_key',
            'client_key'
        ];

        foreach ($sensitiveKeys as $key) {
            if (isset($data[$key])) {
                $data[$key] = '[FILTERED]';
            }
        }

        // Sanitize nested data
        if (isset($data['customer_details'])) {
            $customer = $data['customer_details'];
            if (isset($customer['email'])) {
                $customer['email'] = $this->maskEmail($customer['email']);
            }
            if (isset($customer['phone'])) {
                $customer['phone'] = $this->maskPhone($customer['phone']);
            }
            $data['customer_details'] = $customer;
        }

        // Sanitize credit card data
        if (isset($data['credit_card'])) {
            $cc = $data['credit_card'];
            if (isset($cc['token_id'])) {
                $cc['token_id'] = '[FILTERED]';
            }
            if (isset($cc['card_number'])) {
                $cc['card_number'] = $this->maskCardNumber($cc['card_number']);
            }
            $data['credit_card'] = $cc;
        }

        return $data;
    }

    /**
     * Mask email untuk logging
     *
     * @param string $email
     * @return string
     */
    private function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        if (count($parts) == 2) {
            $name = substr($parts[0], 0, 2) . str_repeat('*', strlen($parts[0]) - 2);
            return $name . '@' . $parts[1];
        }
        return str_repeat('*', strlen($email));
    }

    /**
     * Mask phone number untuk logging
     *
     * @param string $phone
     * @return string
     */
    private function maskPhone(string $phone): string
    {
        $length = strlen($phone);
        if ($length > 4) {
            return substr($phone, 0, 2) . str_repeat('*', $length - 4) . substr($phone, -2);
        }
        return str_repeat('*', $length);
    }

    /**
     * Mask card number untuk logging
     *
     * @param string $cardNumber
     * @return string
     */
    private function maskCardNumber(string $cardNumber): string
    {
        $length = strlen($cardNumber);
        if ($length > 4) {
            return str_repeat('*', $length - 4) . substr($cardNumber, -4);
        }
        return str_repeat('*', $length);
    }

    /**
     * Get nested value dari array menggunakan dot notation
     *
     * @param array $array
     * @param string $key
     * @return mixed
     */
    private function getNestedValue(array $array, string $key)
    {
        $keys = explode('.', $key);
        $value = $array;

        foreach ($keys as $k) {
            if (!is_array($value) || !array_key_exists($k, $value)) {
                return null;
            }
            $value = $value[$k];
        }

        return $value;
    }

    /**
     * Set nested value pada array menggunakan dot notation
     *
     * @param array &$array
     * @param string $key
     * @param mixed $value
     * @return void
     */
    private function setNestedValue(array &$array, string $key, $value): void
    {
        $keys = explode('.', $key);
        $current = &$array;

        foreach ($keys as $k) {
            if (!isset($current[$k]) || !is_array($current[$k])) {
                $current[$k] = [];
            }
            $current = &$current[$k];
        }

        $current = $value;
    }

    /**
     * Generate secure random string
     *
     * @param int $length
     * @return string
     */
    public function generateSecureRandomString(int $length = 32): string
    {
        return Str::random($length);
    }

    /**
     * Hash password atau sensitive data
     *
     * @param string $data
     * @return string
     */
    public function hashSensitiveData(string $data): string
    {
        return hash('sha256', $data . config('app.key'));
    }

    /**
     * Verify data integrity
     *
     * @param string $data
     * @param string $hash
     * @return bool
     */
    public function verifyDataIntegrity(string $data, string $hash): bool
    {
        $expectedHash = $this->hashSensitiveData($data);
        return hash_equals($expectedHash, $hash);
    }
}