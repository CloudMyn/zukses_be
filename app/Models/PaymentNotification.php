<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PaymentNotification
 *
 * Model untuk menyimpan notifikasi pembayaran dari Midtrans
 * Menyediakan fungsi-fungsi untuk management webhook notifications
 *
 * @author Zukses Development Team
 * @version 1.0.0
 * @property int $id
 * @property string $notification_id
 * @property string $transaction_id
 * @property string $order_id
 * @property string $transaction_status
 * @property string|null $payment_type
 * @property string $signature_key
 * @property float $gross_amount
 * @property string|null $payment_code
 * @property string|null $approval_code
 * @property string|null $bank
 * @property string|null $va_number
 * @property string|null $bill_key
 * @property string|null $biller_code
 * @property Carbon $transaction_time
 * @property Carbon|null $settlement_time
 * @property Carbon|null $expiry_time
 * @property string $processing_status
 * @property string|null $error_message
 * @property array $raw_payload
 * @property array|null $processed_data
 * @property array|null $response_data
 * @property string $ip_address
 * @property string|null $user_agent
 * @property int $retry_count
 * @property Carbon|null $last_retry_at
 * @property Carbon $received_at
 * @property Carbon|null $processed_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class PaymentNotification extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_notifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'notification_id',
        'transaction_id',
        'order_id',
        'transaction_status',
        'payment_type',
        'signature_key',
        'gross_amount',
        'payment_code',
        'approval_code',
        'bank',
        'va_number',
        'bill_key',
        'biller_code',
        'transaction_time',
        'settlement_time',
        'expiry_time',
        'processing_status',
        'error_message',
        'raw_payload',
        'processed_data',
        'response_data',
        'ip_address',
        'user_agent',
        'retry_count',
        'last_retry_at',
        'received_at',
        'processed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'gross_amount' => 'decimal:2',
        'raw_payload' => 'array',
        'processed_data' => 'array',
        'response_data' => 'array',
        'retry_count' => 'integer',
        'transaction_time' => 'datetime',
        'settlement_time' => 'datetime',
        'expiry_time' => 'datetime',
        'last_retry_at' => 'datetime',
        'received_at' => 'datetime',
        'processed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'signature_key',
        'raw_payload',
        'response_data',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'formatted_amount',
        'status_label',
        'payment_type_label',
        'processing_status_label',
        'is_processed',
        'is_failed',
        'is_duplicate',
        'time_since_received',
    ];

    /**
     * Get the payment transaction associated with the notification.
     *
     * @return BelongsTo
     */
    public function paymentTransaction(): BelongsTo
    {
        return $this->belongsTo(PaymentTransaction::class, 'transaction_id', 'transaction_id');
    }

    /**
     * Get formatted gross amount.
     *
     * @return string
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'IDR ' . number_format($this->gross_amount, 0, ',', '.');
    }

    /**
     * Get transaction status label in Indonesian.
     *
     * @return string
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'pending' => 'Menunggu Pembayaran',
            'authorize' => 'Diautorisasi',
            'capture' => 'Ditangkap',
            'settlement' => 'Pembayaran Berhasil',
            'deny' => 'Ditolak',
            'cancel' => 'Dibatalkan',
            'expire' => 'Kadaluarsa',
            'refund' => 'Dikembalikan',
            'partial_refund' => 'Dikembalikan Sebagian',
            'failure' => 'Gagal',
        ];

        return $labels[$this->transaction_status] ?? $this->transaction_status;
    }

    /**
     * Get payment type label in Indonesian.
     *
     * @return string
     */
    public function getPaymentTypeLabelAttribute(): string
    {
        $labels = [
            'credit_card' => 'Kartu Kredit',
            'bank_transfer' => 'Transfer Bank',
            'echannel' => 'Mandiri E-Channel',
            'permata_va' => 'Virtual Account Permata',
            'bca_va' => 'Virtual Account BCA',
            'bni_va' => 'Virtual Account BNI',
            'bri_va' => 'Virtual Account BRI',
            'cimb_va' => 'Virtual Account CIMB',
            'other_va' => 'Virtual Account Lainnya',
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'qris' => 'QRIS',
        ];

        return $labels[$this->payment_type] ?? $this->payment_type ?? 'Tidak Diketahui';
    }

    /**
     * Get processing status label in Indonesian.
     *
     * @return string
     */
    public function getProcessingStatusLabelAttribute(): string
    {
        $labels = [
            'received' => 'Diterima',
            'processing' => 'Sedang Diproses',
            'processed' => 'Telah Diproses',
            'failed' => 'Gagal',
            'duplicate' => 'Duplikat',
        ];

        return $labels[$this->processing_status] ?? $this->processing_status;
    }

    /**
     * Check if notification is processed.
     *
     * @return bool
     */
    public function getIsProcessedAttribute(): bool
    {
        return $this->processing_status === 'processed';
    }

    /**
     * Check if notification processing failed.
     *
     * @return bool
     */
    public function getIsFailedAttribute(): bool
    {
        return $this->processing_status === 'failed';
    }

    /**
     * Check if notification is a duplicate.
     *
     * @return bool
     */
    public function getIsDuplicateAttribute(): bool
    {
        return $this->processing_status === 'duplicate';
    }

    /**
     * Get time since notification was received.
     *
     * @return string
     */
    public function getTimeSinceReceivedAttribute(): string
    {
        return $this->received_at->diffForHumans(now(), true) . ' yang lalu';
    }

    /**
     * Scope a query to only include notifications with a given status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('transaction_status', $status);
    }

    /**
     * Scope a query to only include notifications with a given processing status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $processingStatus
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByProcessingStatus($query, string $processingStatus)
    {
        return $query->where('processing_status', $processingStatus);
    }

    /**
     * Scope a query to only include failed notifications.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFailed($query)
    {
        return $query->where('processing_status', 'failed');
    }

    /**
     * Scope a query to only include unprocessed notifications.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnprocessed($query)
    {
        return $query->whereIn('processing_status', ['received', 'processing']);
    }

    /**
     * Scope a query to only include notifications for a specific transaction.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $transactionId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByTransaction($query, string $transactionId)
    {
        return $query->where('transaction_id', $transactionId);
    }

    /**
     * Scope a query to only include notifications from the last N hours.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $hours
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLastHours($query, int $hours)
    {
        return $query->where('received_at', '>=', now()->subHours($hours));
    }

    /**
     * Scope a query to only include notifications that need retry.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNeedsRetry($query)
    {
        return $query->whereIn('processing_status', ['failed'])
            ->where('retry_count', '<', 3)
            ->where(function ($q) {
                $q->whereNull('last_retry_at')
                  ->orWhere('last_retry_at', '<', now()->subMinutes(5));
            });
    }

    /**
     * Mark notification as processed.
     *
     * @param array $responseData
     * @return bool
     */
    public function markAsProcessed(array $responseData = []): bool
    {
        $this->processing_status = 'processed';
        $this->processed_at = now();
        $this->response_data = $responseData;
        $this->error_message = null;

        return $this->save();
    }

    /**
     * Mark notification as failed.
     *
     * @param string $errorMessage
     * @return bool
     */
    public function markAsFailed(string $errorMessage): bool
    {
        $this->processing_status = 'failed';
        $this->error_message = $errorMessage;
        $this->retry_count++;
        $this->last_retry_at = now();

        return $this->save();
    }

    /**
     * Mark notification as duplicate.
     *
     * @return bool
     */
    public function markAsDuplicate(): bool
    {
        $this->processing_status = 'duplicate';
        $this->processed_at = now();

        return $this->save();
    }

    /**
     * Mark notification as processing.
     *
     * @return bool
     */
    public function markAsProcessing(): bool
    {
        $this->processing_status = 'processing';

        return $this->save();
    }

    /**
     * Create a new payment notification from webhook data.
     *
     * @param array $webhookData
     * @return static
     */
    public static function createFromWebhook(array $webhookData): self
    {
        return self::create([
            'notification_id' => uniqid('notif_', true),
            'transaction_id' => $webhookData['order_id'],
            'order_id' => $webhookData['order_id'],
            'transaction_status' => $webhookData['transaction_status'],
            'payment_type' => $webhookData['payment_type'] ?? null,
            'signature_key' => $webhookData['signature_key'],
            'gross_amount' => (float) $webhookData['gross_amount'],
            'payment_code' => $webhookData['payment_code'] ?? null,
            'approval_code' => $webhookData['approval_code'] ?? null,
            'bank' => $webhookData['bank'] ?? null,
            'va_number' => $webhookData['va_number'] ?? null,
            'bill_key' => $webhookData['bill_key'] ?? null,
            'biller_code' => $webhookData['biller_code'] ?? null,
            'transaction_time' => $webhookData['transaction_time'] ?? now(),
            'settlement_time' => $webhookData['settlement_time'] ?? null,
            'expiry_time' => $webhookData['expiry_time'] ?? null,
            'processing_status' => 'received',
            'raw_payload' => $webhookData,
            'processed_data' => $webhookData,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'retry_count' => 0,
            'received_at' => now(),
        ]);
    }

    /**
     * Check if notification can be retried.
     *
     * @return bool
     */
    public function canBeRetried(): bool
    {
        if ($this->processing_status !== 'failed') {
            return false;
        }

        if ($this->retry_count >= 3) {
            return false;
        }

        // Don't retry if last retry was less than 5 minutes ago
        if ($this->last_retry_at && $this->last_retry_at->diffInMinutes(now()) < 5) {
            return false;
        }

        return true;
    }

    /**
     * Get retry delay in seconds.
     *
     * @return int
     */
    public function getRetryDelay(): int
    {
        // Exponential backoff: 30s, 60s, 300s
        $delays = [30, 60, 300];
        return $delays[min($this->retry_count, count($delays) - 1)];
    }

    /**
     * Get notification summary.
     *
     * @return array
     */
    public function getNotificationSummary(): array
    {
        return [
            'notification_id' => $this->notification_id,
            'transaction_id' => $this->transaction_id,
            'order_id' => $this->order_id,
            'transaction_status' => $this->transaction_status,
            'status_label' => $this->status_label,
            'payment_type' => $this->payment_type,
            'payment_type_label' => $this->payment_type_label,
            'gross_amount' => $this->gross_amount,
            'formatted_amount' => $this->formatted_amount,
            'processing_status' => $this->processing_status,
            'processing_status_label' => $this->processing_status_label,
            'is_processed' => $this->is_processed,
            'is_failed' => $this->is_failed,
            'is_duplicate' => $this->is_duplicate,
            'retry_count' => $this->retry_count,
            'can_be_retried' => $this->canBeRetried(),
            'retry_delay' => $this->getRetryDelay(),
            'error_message' => $this->error_message,
            'received_at' => $this->received_at->toISOString(),
            'processed_at' => $this->processed_at?->toISOString(),
            'time_since_received' => $this->time_since_received,
        ];
    }

    /**
     * Get payment details from the notification.
     *
     * @return array
     */
    public function getPaymentDetails(): array
    {
        return [
            'payment_type' => $this->payment_type,
            'bank' => $this->bank,
            'va_number' => $this->va_number,
            'payment_code' => $this->payment_code,
            'approval_code' => $this->approval_code,
            'bill_key' => $this->bill_key,
            'biller_code' => $this->biller_code,
            'amount' => $this->gross_amount,
            'transaction_time' => $this->transaction_time->toISOString(),
            'settlement_time' => $this->settlement_time?->toISOString(),
            'expiry_time' => $this->expiry_time?->toISOString(),
        ];
    }

    /**
     * Check if the notification is for a successful payment.
     *
     * @return bool
     */
    public function isSuccessfulPayment(): bool
    {
        return in_array($this->transaction_status, ['settlement', 'capture']);
    }

    /**
     * Check if the notification is for a failed payment.
     *
     * @return bool
     */
    public function isFailedPayment(): bool
    {
        return in_array($this->transaction_status, ['deny', 'cancel', 'expire', 'failure']);
    }

    /**
     * Check if the notification is for a refund.
     *
     * @return bool
     */
    public function isRefund(): bool
    {
        return in_array($this->transaction_status, ['refund', 'partial_refund']);
    }

    /**
     * Get sanitized payload for display.
     *
     * @return array
     */
    public function getSanitizedPayload(): array
    {
        if (!$this->raw_payload) {
            return [];
        }

        $sensitiveKeys = [
            'signature_key',
            'card_number',
            'token',
            'approval_code'
        ];

        return $this->sanitizePayload($this->raw_payload, $sensitiveKeys);
    }

    /**
     * Sanitize payload by removing sensitive data.
     *
     * @param array $payload
     * @param array $sensitiveKeys
     * @return array
     */
    private function sanitizePayload(array $payload, array $sensitiveKeys): array
    {
        foreach ($payload as $key => $value) {
            if (is_array($value)) {
                $payload[$key] = $this->sanitizePayload($value, $sensitiveKeys);
            } elseif (in_array($key, $sensitiveKeys)) {
                $payload[$key] = '[FILTERED]';
            }
        }

        return $payload;
    }

    /**
     * Get the related payment transaction if exists.
     *
     * @return PaymentTransaction|null
     */
    public function getRelatedPaymentTransaction(): ?PaymentTransaction
    {
        return $this->paymentTransaction;
    }

    /**
     * Create activity log for this notification.
     *
     * @param string $action
     * @param array $context
     * @return void
     */
    public function logActivity(string $action, array $context = []): void
    {
        PaymentLog::create([
            'transaction_id' => $this->transaction_id,
            'payment_transaction_id' => $this->paymentTransaction?->id,
            'user_id' => $this->paymentTransaction?->user_id,
            'pesanan_id' => $this->paymentTransaction?->pesanan_id,
            'log_type' => 'webhook',
            'log_level' => 'info',
            'action' => $action,
            'message' => "Notification {$action}",
            'request_data' => json_encode(array_merge($context, [
                'notification_id' => $this->notification_id,
                'processing_status' => $this->processing_status,
            ])),
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'logged_at' => now(),
        ]);
    }
}