<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PaymentLog
 *
 * Model untuk menyimpan log aktivitas pembayaran
 * Menyediakan fungsi-fungsi untuk logging dan audit trail
 *
 * @author Zukses Development Team
 * @version 1.0.0
 * @property int $id
 * @property string $transaction_id
 * @property int|null $payment_transaction_id
 * @property int|null $user_id
 * @property int|null $pesanan_id
 * @property string $log_type
 * @property string $log_level
 * @property string|null $event_name
 * @property string $action
 * @property string $message
 * @property string|null $endpoint
 * @property string|null $method
 * @property string|null $status_code
 * @property array|null $request_data
 * @property array|null $response_data
 * @property array|null $headers
 * @property int|null $execution_time_ms
 * @property int|null $memory_usage_mb
 * @property string|null $error_code
 * @property string|null $error_message
 * @property string|null $stack_trace
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $session_id
 * @property string|null $request_id
 * @property array|null $metadata
 * @property array|null $context
 * @property Carbon $logged_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class PaymentLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_id',
        'payment_transaction_id',
        'user_id',
        'pesanan_id',
        'log_type',
        'log_level',
        'event_name',
        'action',
        'message',
        'endpoint',
        'method',
        'status_code',
        'request_data',
        'response_data',
        'headers',
        'execution_time_ms',
        'memory_usage_mb',
        'error_code',
        'error_message',
        'stack_trace',
        'ip_address',
        'user_agent',
        'session_id',
        'request_id',
        'metadata',
        'context',
        'logged_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'payment_transaction_id' => 'integer',
        'user_id' => 'integer',
        'pesanan_id' => 'integer',
        'execution_time_ms' => 'integer',
        'memory_usage_mb' => 'integer',
        'request_data' => 'array',
        'response_data' => 'array',
        'headers' => 'array',
        'metadata' => 'array',
        'context' => 'array',
        'logged_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'stack_trace',
        'request_data',
        'response_data',
        'headers',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'log_level_label',
        'log_type_label',
        'formatted_execution_time',
    ];

    /**
     * Get the payment transaction that owns the payment log.
     *
     * @return BelongsTo
     */
    public function paymentTransaction(): BelongsTo
    {
        return $this->belongsTo(PaymentTransaction::class, 'payment_transaction_id');
    }

    /**
     * Get the user that owns the payment log.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the pesanan (order) that owns the payment log.
     *
     * @return BelongsTo
     */
    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Pesanan::class, 'pesanan_id');
    }

    /**
     * Get log level label in Indonesian.
     *
     * @return string
     */
    public function getLogLevelLabelAttribute(): string
    {
        $labels = [
            'debug' => 'Debug',
            'info' => 'Info',
            'warning' => 'Peringatan',
            'error' => 'Error',
            'critical' => 'Kritis',
        ];

        return $labels[$this->log_level] ?? $this->log_level;
    }

    /**
     * Get log type label in Indonesian.
     *
     * @return string
     */
    public function getLogTypeLabelAttribute(): string
    {
        $labels = [
            'request' => 'Request',
            'response' => 'Response',
            'webhook' => 'Webhook',
            'callback' => 'Callback',
            'notification' => 'Notifikasi',
            'error' => 'Error',
            'info' => 'Info',
            'debug' => 'Debug',
        ];

        return $labels[$this->log_type] ?? $this->log_type;
    }

    /**
     * Get formatted execution time.
     *
     * @return string|null
     */
    public function getFormattedExecutionTimeAttribute(): ?string
    {
        if (!$this->execution_time_ms) {
            return null;
        }

        if ($this->execution_time_ms < 1000) {
            return $this->execution_time_ms . ' ms';
        }

        return number_format($this->execution_time_ms / 1000, 2) . ' detik';
    }

    /**
     * Scope a query to only include logs of a given type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $logType
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByLogType($query, string $logType)
    {
        return $query->where('log_type', $logType);
    }

    /**
     * Scope a query to only include logs of a given level.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $logLevel
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByLogLevel($query, string $logLevel)
    {
        return $query->where('log_level', $logLevel);
    }

    /**
     * Scope a query to only include error logs.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeErrors($query)
    {
        return $query->whereIn('log_level', ['error', 'critical']);
    }

    /**
     * Scope a query to only include logs for a specific transaction.
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
     * Scope a query to only include logs for a specific user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to filter by date range.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $startDate
     * @param string $endDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDateRange($query, string $startDate, string $endDate)
    {
        return $query->whereBetween('logged_at', [$startDate, $endDate]);
    }

    /**
     * Scope a query to only include slow requests.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $thresholdMs
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSlow($query, int $thresholdMs = 1000)
    {
        return $query->where('execution_time_ms', '>=', $thresholdMs);
    }

    /**
     * Scope a query to only include logs from the last N hours.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $hours
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLastHours($query, int $hours)
    {
        return $query->where('logged_at', '>=', now()->subHours($hours));
    }

    /**
     * Create a new payment log entry.
     *
     * @param array $data
     * @return static
     */
    public static function createLog(array $data): self
    {
        return self::create([
            'transaction_id' => $data['transaction_id'] ?? null,
            'payment_transaction_id' => $data['payment_transaction_id'] ?? null,
            'user_id' => $data['user_id'] ?? null,
            'pesanan_id' => $data['pesanan_id'] ?? null,
            'log_type' => $data['log_type'] ?? 'info',
            'log_level' => $data['log_level'] ?? 'info',
            'event_name' => $data['event_name'] ?? null,
            'action' => $data['action'] ?? 'unknown',
            'message' => $data['message'] ?? '',
            'endpoint' => $data['endpoint'] ?? null,
            'method' => $data['method'] ?? null,
            'status_code' => $data['status_code'] ?? null,
            'request_data' => $data['request_data'] ?? null,
            'response_data' => $data['response_data'] ?? null,
            'headers' => $data['headers'] ?? null,
            'execution_time_ms' => $data['execution_time_ms'] ?? null,
            'memory_usage_mb' => $data['memory_usage_mb'] ?? null,
            'error_code' => $data['error_code'] ?? null,
            'error_message' => $data['error_message'] ?? null,
            'stack_trace' => $data['stack_trace'] ?? null,
            'ip_address' => $data['ip_address'] ?? request()->ip(),
            'user_agent' => $data['user_agent'] ?? request()->userAgent(),
            'session_id' => $data['session_id'] ?? session()->getId(),
            'request_id' => $data['request_id'] ?? null,
            'metadata' => $data['metadata'] ?? null,
            'context' => $data['context'] ?? null,
            'logged_at' => $data['logged_at'] ?? now(),
        ]);
    }

    /**
     * Log an API request.
     *
     * @param array $requestData
     * @return static
     */
    public static function logRequest(array $requestData): self
    {
        return self::createLog([
            'log_type' => 'request',
            'log_level' => 'info',
            'action' => 'api_request',
            'message' => 'API Request received',
            'endpoint' => $requestData['endpoint'] ?? null,
            'method' => $requestData['method'] ?? null,
            'request_data' => $requestData['data'] ?? null,
            'headers' => $requestData['headers'] ?? null,
            'execution_time_ms' => $requestData['execution_time_ms'] ?? null,
            'metadata' => $requestData['metadata'] ?? null,
        ]);
    }

    /**
     * Log an API response.
     *
     * @param array $responseData
     * @return static
     */
    public static function logResponse(array $responseData): self
    {
        return self::createLog([
            'log_type' => 'response',
            'log_level' => 'info',
            'action' => 'api_response',
            'message' => 'API Response sent',
            'endpoint' => $responseData['endpoint'] ?? null,
            'method' => $responseData['method'] ?? null,
            'status_code' => $responseData['status_code'] ?? null,
            'response_data' => $responseData['data'] ?? null,
            'headers' => $responseData['headers'] ?? null,
            'execution_time_ms' => $responseData['execution_time_ms'] ?? null,
            'metadata' => $responseData['metadata'] ?? null,
        ]);
    }

    /**
     * Log a webhook notification.
     *
     * @param array $notificationData
     * @return static
     */
    public static function logWebhook(array $notificationData): self
    {
        return self::createLog([
            'log_type' => 'webhook',
            'log_level' => 'info',
            'action' => 'webhook_received',
            'message' => 'Webhook notification received',
            'request_data' => $notificationData['data'] ?? null,
            'ip_address' => $notificationData['ip_address'] ?? null,
            'user_agent' => $notificationData['user_agent'] ?? null,
            'metadata' => $notificationData['metadata'] ?? null,
        ]);
    }

    /**
     * Log an error.
     *
     * @param array $errorData
     * @return static
     */
    public static function logError(array $errorData): self
    {
        return self::createLog([
            'log_type' => 'error',
            'log_level' => 'error',
            'action' => 'error_occurred',
            'message' => $errorData['message'] ?? 'An error occurred',
            'error_code' => $errorData['error_code'] ?? null,
            'error_message' => $errorData['error_message'] ?? null,
            'stack_trace' => $errorData['stack_trace'] ?? null,
            'context' => $errorData['context'] ?? null,
            'metadata' => $errorData['metadata'] ?? null,
        ]);
    }

    /**
     * Log a critical error.
     *
     * @param array $errorData
     * @return static
     */
    public static function logCritical(array $errorData): self
    {
        return self::createLog([
            'log_type' => 'error',
            'log_level' => 'critical',
            'action' => 'critical_error',
            'message' => $errorData['message'] ?? 'A critical error occurred',
            'error_code' => $errorData['error_code'] ?? null,
            'error_message' => $errorData['error_message'] ?? null,
            'stack_trace' => $errorData['stack_trace'] ?? null,
            'context' => $errorData['context'] ?? null,
            'metadata' => $errorData['metadata'] ?? null,
        ]);
    }

    /**
     * Get sanitized request data for display.
     *
     * @return array|null
     */
    public function getSanitizedRequestData(): ?array
    {
        if (!$this->request_data) {
            return null;
        }

        $sensitiveKeys = [
            'password', 'token', 'secret', 'key', 'card_number',
            'cvv', 'signature', 'authorization'
        ];

        return $this->sanitizeArray($this->request_data, $sensitiveKeys);
    }

    /**
     * Get sanitized response data for display.
     *
     * @return array|null
     */
    public function getSanitizedResponseData(): ?array
    {
        if (!$this->response_data) {
            return null;
        }

        $sensitiveKeys = [
            'token', 'secret', 'key', 'signature',
            'card_number', 'authorization'
        ];

        return $this->sanitizeArray($this->response_data, $sensitiveKeys);
    }

    /**
     * Sanitize array by removing sensitive data.
     *
     * @param array $array
     * @param array $sensitiveKeys
     * @return array
     */
    private function sanitizeArray(array $array, array $sensitiveKeys): array
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->sanitizeArray($value, $sensitiveKeys);
            } elseif (in_array(strtolower($key), $sensitiveKeys)) {
                $array[$key] = '[FILTERED]';
            }
        }

        return $array;
    }

    /**
     * Check if the log represents an error.
     *
     * @return bool
     */
    public function isError(): bool
    {
        return in_array($this->log_level, ['error', 'critical']);
    }

    /**
     * Check if the log represents a slow request.
     *
     * @param int $thresholdMs
     * @return bool
     */
    public function isSlow(int $thresholdMs = 1000): bool
    {
        return $this->execution_time_ms && $this->execution_time_ms > $thresholdMs;
    }

    /**
     * Get log summary.
     *
     * @return array
     */
    public function getLogSummary(): array
    {
        return [
            'id' => $this->id,
            'transaction_id' => $this->transaction_id,
            'log_type' => $this->log_type,
            'log_type_label' => $this->log_type_label,
            'log_level' => $this->log_level,
            'log_level_label' => $this->log_level_label,
            'action' => $this->action,
            'message' => $this->message,
            'endpoint' => $this->endpoint,
            'method' => $this->method,
            'status_code' => $this->status_code,
            'execution_time_ms' => $this->execution_time_ms,
            'formatted_execution_time' => $this->formatted_execution_time,
            'is_error' => $this->isError(),
            'is_slow' => $this->isSlow(),
            'ip_address' => $this->ip_address,
            'logged_at' => $this->logged_at->toISOString(),
        ];
    }
}