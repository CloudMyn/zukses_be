<?php

namespace App\Services\Midtrans;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\PaymentTransaction;
use App\Models\PaymentLog;
use App\Models\PaymentNotification;
use App\Services\Midtrans\MidtransService;

/**
 * PaymentTransactionManager
 *
 * Manager untuk mengelola lifecycle transaksi pembayaran
 * Menyediakan fungsi CRUD dan status management untuk payment transactions
 *
 * @author Zukses Development Team
 * @version 1.0.0
 */
class PaymentTransactionManager
{
    /**
     * Midtrans service instance
     */
    protected MidtransService $midtransService;

    /**
     * Constructor
     *
     * @param MidtransService $midtransService
     */
    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Create payment transaction
     *
     * @param array $transactionData
     * @return PaymentTransaction
     * @throws Exception
     */
    public function createTransaction(array $transactionData): PaymentTransaction
    {
        try {
            Log::info('PaymentTransactionManager: Membuat payment transaction', [
                'order_number' => $transactionData['order_number'] ?? 'unknown',
                'user_id' => $transactionData['user_id'] ?? 'unknown',
                'amount' => $transactionData['gross_amount'] ?? 0
            ]);

            // Validate required fields
            $this->validateCreateTransactionData($transactionData);

            // Generate transaction ID jika belum ada
            if (!isset($transactionData['transaction_id'])) {
                $transactionData['transaction_id'] = $this->generateTransactionId(
                    $transactionData['user_id'],
                    $transactionData['order_number']
                );
            }

            // Prepare data for database
            $dbData = $this->prepareDatabaseData($transactionData);

            // Use database transaction untuk data consistency
            return DB::transaction(function () use ($dbData, $transactionData) {
                // Create payment transaction
                $paymentTransaction = PaymentTransaction::create($dbData);

                // Log the creation
                $this->logTransactionActivity($paymentTransaction, 'created', [
                    'source' => 'PaymentTransactionManager',
                    'data' => $this->sanitizeLogData($transactionData)
                ]);

                Log::info('PaymentTransactionManager: Payment transaction berhasil dibuat', [
                    'transaction_id' => $paymentTransaction->transaction_id,
                    'id' => $paymentTransaction->id
                ]);

                return $paymentTransaction;
            });

        } catch (Exception $e) {
            Log::error('PaymentTransactionManager: Gagal membuat payment transaction', [
                'order_number' => $transactionData['order_number'] ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new Exception('Gagal membuat transaksi pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Update transaction status
     *
     * @param string $transactionId
     * @param string $status
     * @param array $updateData
     * @return PaymentTransaction
     * @throws Exception
     */
    public function updateTransactionStatus(string $transactionId, string $status, array $updateData = []): PaymentTransaction
    {
        try {
            Log::info('PaymentTransactionManager: Update status transaksi', [
                'transaction_id' => $transactionId,
                'new_status' => $status
            ]);

            // Find transaction
            $transaction = PaymentTransaction::where('transaction_id', $transactionId)->firstOrFail();

            // Check if status is valid
            $this->validateTransactionStatus($status);

            // Prepare update data
            $updateData['transaction_status'] = $status;

            // Add timestamp based on status
            switch ($status) {
                case 'settlement':
                    $updateData['settlement_time'] = now();
                    $updateData['paid_at'] = now();
                    break;
                case 'cancel':
                case 'deny':
                case 'expire':
                    $updateData['paid_at'] = null;
                    break;
            }

            // Update within database transaction
            $updatedTransaction = DB::transaction(function () use ($transaction, $updateData, $status) {
                $oldStatus = $transaction->transaction_status;

                // Update transaction
                $transaction->update($updateData);

                // Log status change
                $this->logTransactionActivity($transaction, 'status_updated', [
                    'old_status' => $oldStatus,
                    'new_status' => $status,
                    'update_data' => $this->sanitizeLogData($updateData)
                ]);

                Log::info('PaymentTransactionManager: Status transaksi berhasil diupdate', [
                    'transaction_id' => $transaction->transaction_id,
                    'old_status' => $oldStatus,
                    'new_status' => $status
                ]);

                return $transaction->fresh();
            });

            return $updatedTransaction;

        } catch (Exception $e) {
            Log::error('PaymentTransactionManager: Gagal update status transaksi', [
                'transaction_id' => $transactionId,
                'status' => $status,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new Exception('Gagal update status transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Get transaction by ID
     *
     * @param string $transactionId
     * @return PaymentTransaction
     * @throws Exception
     */
    public function getTransaction(string $transactionId): PaymentTransaction
    {
        try {
            $transaction = PaymentTransaction::where('transaction_id', $transactionId)->firstOrFail();

            Log::info('PaymentTransactionManager: Transaksi ditemukan', [
                'transaction_id' => $transactionId,
                'status' => $transaction->transaction_status
            ]);

            return $transaction;

        } catch (Exception $e) {
            Log::error('PaymentTransactionManager: Transaksi tidak ditemukan', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage()
            ]);
            throw new Exception('Transaksi tidak ditemukan: ' . $transactionId);
        }
    }

    /**
     * Get transaction by order number
     *
     * @param string $orderNumber
     * @return PaymentTransaction|null
     */
    public function getTransactionByOrderNumber(string $orderNumber): ?PaymentTransaction
    {
        try {
            $transaction = PaymentTransaction::where('order_number', $orderNumber)->first();

            if ($transaction) {
                Log::info('PaymentTransactionManager: Transaksi ditemukan berdasarkan order number', [
                    'order_number' => $orderNumber,
                    'transaction_id' => $transaction->transaction_id,
                    'status' => $transaction->transaction_status
                ]);
            } else {
                Log::info('PaymentTransactionManager: Transaksi tidak ditemukan berdasarkan order number', [
                    'order_number' => $orderNumber
                ]);
            }

            return $transaction;

        } catch (Exception $e) {
            Log::error('PaymentTransactionManager: Error mencari transaksi berdasarkan order number', [
                'order_number' => $orderNumber,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Get user transactions with pagination
     *
     * @param int $userId
     * @param int $perPage
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getUserTransactions(int $userId, int $perPage = 10, array $filters = [])
    {
        try {
            $query = PaymentTransaction::where('user_id', $userId);

            // Apply filters
            if (isset($filters['status'])) {
                $query->where('transaction_status', $filters['status']);
            }

            if (isset($filters['payment_type'])) {
                $query->where('payment_type', $filters['payment_type']);
            }

            if (isset($filters['date_from'])) {
                $query->where('created_at', '>=', $filters['date_from']);
            }

            if (isset($filters['date_to'])) {
                $query->where('created_at', '<=', $filters['date_to']);
            }

            $transactions = $query->orderBy('created_at', 'desc')
                ->paginate($perPage);

            Log::info('PaymentTransactionManager: Mengambil user transactions', [
                'user_id' => $userId,
                'count' => $transactions->count(),
                'filters' => $filters
            ]);

            return $transactions;

        } catch (Exception $e) {
            Log::error('PaymentTransactionManager: Error mengambil user transactions', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            throw new Exception('Gagal mengambil data transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Process webhook notification
     *
     * @param array $notificationData
     * @return PaymentTransaction
     * @throws Exception
     */
    public function processWebhookNotification(array $notificationData): PaymentTransaction
    {
        try {
            Log::info('PaymentTransactionManager: Memproses webhook notification', [
                'order_id' => $notificationData['order_id'] ?? 'unknown',
                'transaction_status' => $notificationData['transaction_status'] ?? 'unknown',
                'status_code' => $notificationData['status_code'] ?? 'unknown'
            ]);

            // Validate webhook
            if (!$this->midtransService->validateNotification($notificationData)) {
                throw new Exception('Webhook signature tidak valid');
            }

            // Find or create transaction
            $transaction = $this->findOrCreateTransactionFromNotification($notificationData);

            // Check for duplicate notification
            if ($this->isDuplicateNotification($transaction, $notificationData)) {
                Log::info('PaymentTransactionManager: Duplicate notification detected', [
                    'transaction_id' => $transaction->transaction_id
                ]);
                return $transaction;
            }

            // Update transaction from notification
            $updatedTransaction = $this->updateTransactionFromNotification($transaction, $notificationData);

            // Save notification
            $this->saveNotification($notificationData);

            Log::info('PaymentTransactionManager: Webhook notification berhasil diproses', [
                'transaction_id' => $updatedTransaction->transaction_id,
                'status' => $updatedTransaction->transaction_status
            ]);

            return $updatedTransaction;

        } catch (Exception $e) {
            Log::error('PaymentTransactionManager: Gagal memproses webhook notification', [
                'notification_data' => $this->sanitizeLogData($notificationData),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new Exception('Gagal memproses notifikasi: ' . $e->getMessage());
        }
    }

    /**
     * Cancel expired transactions
     *
     * @param int $batchSize
     * @return int Number of cancelled transactions
     */
    public function cancelExpiredTransactions(int $batchSize = 100): int
    {
        try {
            Log::info('PaymentTransactionManager: Membatalkan transaksi kadaluarsa', [
                'batch_size' => $batchSize
            ]);

            $expiredThreshold = Carbon::now()->subHours(24); // Transaksi kadaluarsa setelah 24 jam
            $cancelledCount = 0;

            DB::transaction(function () use ($expiredThreshold, $batchSize, &$cancelledCount) {
                $expiredTransactions = PaymentTransaction::where('transaction_status', 'pending')
                    ->where('created_at', '<', $expiredThreshold)
                    ->limit($batchSize)
                    ->get();

                foreach ($expiredTransactions as $transaction) {
                    // Update status to expired
                    $transaction->update([
                        'transaction_status' => 'expire',
                        'expiry_time' => now()
                    ]);

                    // Log the cancellation
                    $this->logTransactionActivity($transaction, 'auto_expired', [
                        'reason' => 'Auto-expired after 24 hours',
                        'created_at' => $transaction->created_at
                    ]);

                    $cancelledCount++;
                }
            });

            Log::info('PaymentTransactionManager: Transaksi kadaluarsa berhasil dibatalkan', [
                'cancelled_count' => $cancelledCount
            ]);

            return $cancelledCount;

        } catch (Exception $e) {
            Log::error('PaymentTransactionManager: Gagal membatalkan transaksi kadaluarsa', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 0;
        }
    }

    /**
     * Validate create transaction data
     *
     * @param array $data
     * @return void
     * @throws Exception
     */
    private function validateCreateTransactionData(array $data): void
    {
        $required = [
            'user_id',
            'order_number',
            'gross_amount',
            'customer_details',
            'item_details'
        ];

        foreach ($required as $field) {
            if (!isset($data[$field])) {
                throw new Exception("Field {$field} harus diisi");
            }
        }

        // Validate amount
        if ($data['gross_amount'] <= 0) {
            throw new Exception('Gross amount harus lebih dari 0');
        }
    }

    /**
     * Prepare data for database insertion
     *
     * @param array $data
     * @return array
     */
    private function prepareDatabaseData(array $data): array
    {
        return [
            'transaction_id' => $data['transaction_id'],
            'order_number' => $data['order_number'],
            'user_id' => $data['user_id'],
            'pesanan_id' => $data['pesanan_id'] ?? null,
            'gross_amount' => $data['gross_amount'],
            'net_amount' => $this->calculateNetAmount($data),
            'customer_details' => json_encode($data['customer_details']),
            'item_details' => json_encode($data['item_details']),
            'transaction_status' => 'pending',
            'transaction_time' => now(),
            'expiry_time' => $this->calculateExpiryTime($data),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'request_id' => $data['request_id'] ?? null,
            'midtrans_response' => json_encode($data['midtrans_response'] ?? []),
        ];
    }

    /**
     * Calculate net amount (gross amount minus fees)
     *
     * @param array $data
     * @return float
     */
    private function calculateNetAmount(array $data): float
    {
        $grossAmount = $data['gross_amount'];
        $feeAmount = 0;

        // Calculate fee based on payment type
        if (isset($data['payment_type'])) {
            $feeAmount = $this->calculatePaymentFee($data['payment_type'], $grossAmount);
        }

        return $grossAmount - $feeAmount;
    }

    /**
     * Calculate payment fee
     *
     * @param string $paymentType
     * @param float $amount
     * @return float
     */
    private function calculatePaymentFee(string $paymentType, float $amount): float
    {
        // Fee structure (example, adjust according to Midtrans fee)
        $fees = [
            'credit_card' => 0.029, // 2.9%
            'bank_transfer' => 0,
            'echannel' => 4000, // Fixed fee
            'gopay' => 0.02, // 2%
            'shopeepay' => 0.02, // 2%
            'qris' => 0.007, // 0.7%
        ];

        $feeRate = $fees[$paymentType] ?? 0;

        if ($paymentType === 'echannel') {
            return $feeRate; // Fixed fee
        }

        return $amount * $feeRate;
    }

    /**
     * Calculate expiry time
     *
     * @param array $data
     * @return Carbon
     */
    private function calculateExpiryTime(array $data): Carbon
    {
        $timeoutMinutes = config('midtrans.transaction.timeout_minutes', 60);
        return Carbon::now()->addMinutes($timeoutMinutes);
    }

    /**
     * Generate transaction ID
     *
     * @param int $userId
     * @param string $orderNumber
     * @return string
     */
    private function generateTransactionId(int $userId, string $orderNumber): string
    {
        $timestamp = time();
        $hash = substr(hash('sha256', $userId . $orderNumber . $timestamp), 0, 12);
        return "TXN{$timestamp}{$hash}";
    }

    /**
     * Log transaction activity
     *
     * @param PaymentTransaction $transaction
     * @param string $action
     * @param array $context
     * @return void
     */
    private function logTransactionActivity(PaymentTransaction $transaction, string $action, array $context = []): void
    {
        PaymentLog::create([
            'transaction_id' => $transaction->transaction_id,
            'payment_transaction_id' => $transaction->id,
            'user_id' => $transaction->user_id,
            'pesanan_id' => $transaction->pesanan_id,
            'log_type' => 'info',
            'action' => $action,
            'message' => "Transaction {$action}",
            'request_data' => json_encode($context),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'logged_at' => now(),
        ]);
    }

    /**
     * Find or create transaction from notification
     *
     * @param array $notificationData
     * @return PaymentTransaction
     * @throws Exception
     */
    private function findOrCreateTransactionFromNotification(array $notificationData): PaymentTransaction
    {
        // Try to find by order_id or transaction_id
        $transaction = PaymentTransaction::where('order_number', $notificationData['order_id'])
            ->orWhere('midtrans_transaction_id', $notificationData['transaction_id'] ?? null)
            ->first();

        if (!$transaction) {
            throw new Exception("Transaksi tidak ditemukan untuk order: {$notificationData['order_id']}");
        }

        return $transaction;
    }

    /**
     * Check for duplicate notification
     *
     * @param PaymentTransaction $transaction
     * @param array $notificationData
     * @return bool
     */
    private function isDuplicateNotification(PaymentTransaction $transaction, array $notificationData): bool
    {
        return PaymentNotification::where('transaction_id', $notificationData['order_id'])
            ->where('transaction_status', $notificationData['transaction_status'])
            ->where('signature_key', $notificationData['signature_key'])
            ->exists();
    }

    /**
     * Update transaction from notification
     *
     * @param PaymentTransaction $transaction
     * @param array $notificationData
     * @return PaymentTransaction
     */
    private function updateTransactionFromNotification(PaymentTransaction $transaction, array $notificationData): PaymentTransaction
    {
        $updateData = [
            'midtrans_transaction_id' => $notificationData['transaction_id'] ?? $transaction->midtrans_transaction_id,
            'transaction_status' => $notificationData['transaction_status'],
            'payment_type' => $notificationData['payment_type'] ?? $transaction->payment_type,
            'payment_channel' => $notificationData['payment_type'] ?? $transaction->payment_channel,
            'bank' => $notificationData['bank'] ?? $transaction->bank,
            'va_number' => $notificationData['va_number'] ?? $transaction->va_number,
            'bill_key' => $notificationData['bill_key'] ?? $transaction->bill_key,
            'biller_code' => $notificationData['biller_code'] ?? $transaction->biller_code,
            'status_message' => $notificationData['status_message'] ?? $transaction->status_message,
            'fraud_status' => $notificationData['fraud_status'] ?? $transaction->fraud_status,
            'settlement_time' => $notificationData['settlement_time'] ?? $transaction->settlement_time,
            'expiry_time' => $notificationData['expiry_time'] ?? $transaction->expiry_time,
            'midtrans_response' => json_encode(array_merge(
                json_decode($transaction->midtrans_response ?? '{}', true),
                $notificationData
            )),
        ];

        // Update based on status
        switch ($notificationData['transaction_status']) {
            case 'settlement':
            case 'capture':
                $updateData['paid_at'] = now();
                break;
        }

        $transaction->update($updateData);

        // Log the update
        $this->logTransactionActivity($transaction, 'webhook_updated', [
            'notification_status' => $notificationData['transaction_status'],
            'notification_data' => $this->sanitizeLogData($notificationData)
        ]);

        return $transaction->fresh();
    }

    /**
     * Save notification to database
     *
     * @param array $notificationData
     * @return void
     */
    private function saveNotification(array $notificationData): void
    {
        PaymentNotification::create([
            'notification_id' => uniqid('notif_', true),
            'transaction_id' => $notificationData['order_id'],
            'order_id' => $notificationData['order_id'],
            'transaction_status' => $notificationData['transaction_status'],
            'payment_type' => $notificationData['payment_type'] ?? null,
            'gross_amount' => $notificationData['gross_amount'] ?? 0,
            'signature_key' => $notificationData['signature_key'],
            'payment_code' => $notificationData['payment_code'] ?? null,
            'approval_code' => $notificationData['approval_code'] ?? null,
            'bank' => $notificationData['bank'] ?? null,
            'va_number' => $notificationData['va_number'] ?? null,
            'bill_key' => $notificationData['bill_key'] ?? null,
            'biller_code' => $notificationData['biller_code'] ?? null,
            'transaction_time' => $notificationData['transaction_time'] ?? now(),
            'settlement_time' => $notificationData['settlement_time'] ?? null,
            'expiry_time' => $notificationData['expiry_time'] ?? null,
            'raw_payload' => json_encode($notificationData),
            'processed_data' => json_encode($notificationData),
            'processing_status' => 'processed',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'received_at' => now(),
            'processed_at' => now(),
        ]);
    }

    /**
     * Validate transaction status
     *
     * @param string $status
     * @return void
     * @throws Exception
     */
    private function validateTransactionStatus(string $status): void
    {
        $validStatuses = [
            'pending', 'authorize', 'capture', 'settlement',
            'deny', 'cancel', 'expire', 'refund', 'partial_refund', 'failure'
        ];

        if (!in_array($status, $validStatuses)) {
            throw new Exception("Status transaksi tidak valid: {$status}");
        }
    }

    /**
     * Sanitize data for logging
     *
     * @param array $data
     * @return array
     */
    private function sanitizeLogData(array $data): array
    {
        $sensitiveKeys = [
            'signature_key',
            'token_id',
            'card_number',
            'cvv',
            'password'
        ];

        foreach ($sensitiveKeys as $key) {
            if (isset($data[$key])) {
                $data[$key] = '[FILTERED]';
            }
        }

        return $data;
    }
}