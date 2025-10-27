<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

/**
 * PaymentTransaction
 *
 * Model untuk menyimpan data transaksi pembayaran dengan Midtrans
 * Menyediakan fungsi-fungsi untuk management transaksi pembayaran
 *
 * @author Zukses Development Team
 * @version 1.0.0
 * @property int $id
 * @property string $transaction_id
 * @property string $order_number
 * @property int $user_id
 * @property int|null $pesanan_id
 * @property string|null $midtrans_transaction_id
 * @property string|null $midtrans_order_id
 * @property string|null $payment_type
 * @property string|null $payment_channel
 * @property string|null $bank
 * @property string|null $va_number
 * @property string|null $bill_key
 * @property string|null $biller_code
 * @property float $gross_amount
 * @property float $tax_amount
 * @property float $fee_amount
 * @property float $net_amount
 * @property string $transaction_status
 * @property string|null $fraud_status
 * @property string|null $status_message
 * @property Carbon|null $transaction_time
 * @property Carbon|null $settlement_time
 * @property Carbon|null $expiry_time
 * @property Carbon|null $paid_at
 * @property string|null $card_type
 * @property string|null $card_number
 * @property string|null $card_token
 * @property string|null $approval_code
 * @property float $refund_amount
 * @property string|null $refund_reason
 * @property Carbon|null $refunded_at
 * @property array|null $customer_details
 * @property array|null $item_details
 * @property array|null $custom_field
 * @property array|null $midtrans_response
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $request_id
 * @property Carbon|null $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class PaymentTransaction extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_id',
        'order_number',
        'user_id',
        'pesanan_id',
        'midtrans_transaction_id',
        'midtrans_order_id',
        'payment_type',
        'payment_channel',
        'bank',
        'va_number',
        'bill_key',
        'biller_code',
        'gross_amount',
        'tax_amount',
        'fee_amount',
        'net_amount',
        'transaction_status',
        'fraud_status',
        'status_message',
        'transaction_time',
        'settlement_time',
        'expiry_time',
        'paid_at',
        'card_type',
        'card_number',
        'card_token',
        'approval_code',
        'refund_amount',
        'refund_reason',
        'refunded_at',
        'customer_details',
        'item_details',
        'custom_field',
        'midtrans_response',
        'ip_address',
        'user_agent',
        'request_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'pesanan_id' => 'integer',
        'gross_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'fee_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'transaction_time' => 'datetime',
        'settlement_time' => 'datetime',
        'expiry_time' => 'datetime',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
        'customer_details' => 'array',
        'item_details' => 'array',
        'custom_field' => 'array',
        'midtrans_response' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'card_token',
        'ip_address',
        'user_agent',
        'midtrans_response',
        'custom_field',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'formatted_amount',
        'status_label',
        'is_paid',
        'is_expired',
        'is_cancelled',
        'payment_type_label',
        'time_remaining',
    ];

    /**
     * Get the user that owns the payment transaction.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the pesanan (order) that owns the payment transaction.
     *
     * @return BelongsTo
     */
    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Pesanan::class, 'pesanan_id');
    }

    /**
     * Get the payment logs for the payment transaction.
     *
     * @return HasMany
     */
    public function paymentLogs(): HasMany
    {
        return $this->hasMany(PaymentLog::class, 'payment_transaction_id');
    }

    /**
     * Get the payment notifications for the payment transaction.
     *
     * @return HasMany
     */
    public function paymentNotifications(): HasMany
    {
        return $this->hasMany(PaymentNotification::class, 'transaction_id', 'transaction_id');
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
     * Check if transaction is paid.
     *
     * @return bool
     */
    public function getIsPaidAttribute(): bool
    {
        return in_array($this->transaction_status, ['settlement', 'capture']);
    }

    /**
     * Check if transaction is expired.
     *
     * @return bool
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->transaction_status === 'expire' ||
            ($this->transaction_status === 'pending' && $this->expiry_time && $this->expiry_time->isPast());
    }

    /**
     * Check if transaction is cancelled.
     *
     * @return bool
     */
    public function getIsCancelledAttribute(): bool
    {
        return in_array($this->transaction_status, ['cancel', 'deny']);
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
     * Get time remaining until expiry.
     *
     * @return string|null
     */
    public function getTimeRemainingAttribute(): ?string
    {
        if (!$this->expiry_time || $this->transaction_status !== 'pending') {
            return null;
        }

        if ($this->expiry_time->isPast()) {
            return 'Kadaluarsa';
        }

        $diff = $this->expiry_time->diffForHumans(now(), true);

        return $diff . ' lagi';
    }

    /**
     * Scope a query to only include pending transactions.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('transaction_status', 'pending');
    }

    /**
     * Scope a query to only include paid transactions.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePaid($query)
    {
        return $query->whereIn('transaction_status', ['settlement', 'capture']);
    }

    /**
     * Scope a query to only include failed transactions.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFailed($query)
    {
        return $query->whereIn('transaction_status', ['deny', 'cancel', 'expire', 'failure']);
    }

    /**
     * Scope a query to only include refunded transactions.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRefunded($query)
    {
        return $query->whereIn('transaction_status', ['refund', 'partial_refund']);
    }

    /**
     * Scope a query to filter by payment type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $paymentType
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByPaymentType($query, string $paymentType)
    {
        return $query->where('payment_type', $paymentType);
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
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope a query to filter by user.
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
     * Scope a query to filter by order number.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $orderNumber
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByOrderNumber($query, string $orderNumber)
    {
        return $query->where('order_number', $orderNumber);
    }

    /**
     * Scope a query to find expired transactions.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired($query)
    {
        return $query->where('transaction_status', 'pending')
            ->where('expiry_time', '<', now());
    }

    /**
     * Check if transaction can be paid.
     *
     * @return bool
     */
    public function canBePaid(): bool
    {
        return $this->transaction_status === 'pending' &&
            (!$this->expiry_time || $this->expiry_time->isFuture());
    }

    /**
     * Check if transaction can be cancelled.
     *
     * @return bool
     */
    public function canBeCancelled(): bool
    {
        return $this->transaction_status === 'pending' &&
            (!$this->expiry_time || $this->expiry_time->isFuture());
    }

    /**
     * Check if transaction can be refunded.
     *
     * @return bool
     */
    public function canBeRefunded(): bool
    {
        return in_array($this->transaction_status, ['settlement', 'capture']) &&
            $this->refund_amount < $this->gross_amount;
    }

    /**
     * Mark transaction as paid.
     *
     * @param array $paymentDetails
     * @return bool
     */
    public function markAsPaid(array $paymentDetails = []): bool
    {
        $this->transaction_status = 'settlement';
        $this->settlement_time = now();
        $this->paid_at = now();

        if (isset($paymentDetails['payment_type'])) {
            $this->payment_type = $paymentDetails['payment_type'];
        }

        if (isset($paymentDetails['payment_channel'])) {
            $this->payment_channel = $paymentDetails['payment_channel'];
        }

        if (isset($paymentDetails['approval_code'])) {
            $this->approval_code = $paymentDetails['approval_code'];
        }

        if (isset($paymentDetails['bank'])) {
            $this->bank = $paymentDetails['bank'];
        }

        if (isset($paymentDetails['va_number'])) {
            $this->va_number = $paymentDetails['va_number'];
        }

        return $this->save();
    }

    /**
     * Mark transaction as cancelled.
     *
     * @param string $reason
     * @return bool
     */
    public function markAsCancelled(string $reason = ''): bool
    {
        $this->transaction_status = 'cancel';
        $this->status_message = $reason ?: 'Dibatalkan oleh sistem';

        return $this->save();
    }

    /**
     * Mark transaction as expired.
     *
     * @return bool
     */
    public function markAsExpired(): bool
    {
        $this->transaction_status = 'expire';
        $this->status_message = 'Kadaluarsa';
        $this->expiry_time = now();

        return $this->save();
    }

    /**
     * Process refund.
     *
     * @param float $amount
     * @param string $reason
     * @return bool
     */
    public function processRefund(float $amount, string $reason = ''): bool
    {
        if (!$this->canBeRefunded()) {
            return false;
        }

        $this->refund_amount += $amount;
        $this->refund_reason = $reason;
        $this->refunded_at = now();

        // Mark as fully refunded
        if ($this->refund_amount >= $this->gross_amount) {
            $this->transaction_status = 'refund';
        } else {
            $this->transaction_status = 'partial_refund';
        }

        return $this->save();
    }

    /**
     * Get payment instructions based on payment type.
     *
     * @return array|null
     */
    public function getPaymentInstructions(): ?array
    {
        if (!$this->va_number || !$this->bank) {
            return null;
        }

        return [
            'payment_type' => 'virtual_account',
            'bank' => $this->bank,
            'va_number' => $this->va_number,
            'amount' => $this->gross_amount,
            'instructions' => $this->getVaInstructions(),
        ];
    }

    /**
     * Get virtual account payment instructions.
     *
     * @return array
     */
    private function getVaInstructions(): array
    {
        $instructions = [
            'Buka aplikasi mobile banking atau internet banking Anda',
            'Pilih menu Transfer Virtual Account',
            'Masukkan nomor Virtual Account: ' . $this->va_number,
            'Masukkan jumlah pembayaran: Rp ' . number_format($this->gross_amount, 0, ',', '.'),
            'Konfirmasi pembayaran',
        ];

        if ($this->bank === 'mandiri') {
            array_splice($instructions, 2, 0, ['Pilih menu Pembayaran']);
            array_splice($instructions, 3, 0, ['Pilih Multi Payment']);
            array_splice($instructions, 4, 0, ['Masukkan kode perusahaan (biller code): ' . ($this->biller_code ?? '70012')]);
            array_splice($instructions, 5, 0, ['Masukkan nomor Virtual Account: ' . ($this->bill_key ?? $this->va_number)]);
        }

        return $instructions;
    }

    /**
     * Get customer information.
     *
     * @return array|null
     */
    public function getCustomerInfo(): ?array
    {
        if (!$this->customer_details) {
            return null;
        }

        return [
            'name' => trim(($this->customer_details['first_name'] ?? '') . ' ' . ($this->customer_details['last_name'] ?? '')),
            'email' => $this->customer_details['email'] ?? null,
            'phone' => $this->customer_details['phone'] ?? null,
            'billing_address' => $this->customer_details['billing_address'] ?? null,
            'shipping_address' => $this->customer_details['shipping_address'] ?? null,
        ];
    }

    /**
     * Get item information.
     *
     * @return array|null
     */
    public function getItemInfo(): ?array
    {
        if (!$this->item_details) {
            return null;
        }

        return collect($this->item_details)->map(function ($item) {
            return [
                'id' => $item['id'] ?? null,
                'name' => $item['name'] ?? null,
                'price' => $item['price'] ?? 0,
                'quantity' => $item['quantity'] ?? 0,
                'subtotal' => ($item['price'] ?? 0) * ($item['quantity'] ?? 0),
                'category' => $item['category'] ?? null,
            ];
        })->toArray();
    }

    /**
     * Get transaction summary.
     *
     * @return array
     */
    public function getTransactionSummary(): array
    {
        return [
            'transaction_id' => $this->transaction_id,
            'order_number' => $this->order_number,
            'amount' => $this->gross_amount,
            'formatted_amount' => $this->formatted_amount,
            'status' => $this->transaction_status,
            'status_label' => $this->status_label,
            'payment_type' => $this->payment_type,
            'payment_type_label' => $this->payment_type_label,
            'is_paid' => $this->is_paid,
            'is_expired' => $this->is_expired,
            'is_cancelled' => $this->is_cancelled,
            'time_remaining' => $this->time_remaining,
            'created_at' => $this->created_at->toISOString(),
            'paid_at' => $this->paid_at?->toISOString(),
            'expiry_time' => $this->expiry_time?->toISOString(),
            'payment_instructions' => $this->getPaymentInstructions(),
            'customer_info' => $this->getCustomerInfo(),
            'item_info' => $this->getItemInfo(),
        ];
    }

    /**
     * Update from Midtrans response.
     *
     * @param array $midtransResponse
     * @return bool
     */
    public function updateFromMidtransResponse(array $midtransResponse): bool
    {
        $this->midtrans_response = array_merge(
            $this->midtrans_response ?? [],
            $midtransResponse
        );

        $fillableMidtransFields = [
            'midtrans_transaction_id',
            'payment_type',
            'payment_channel',
            'bank',
            'va_number',
            'bill_key',
            'biller_code',
            'transaction_status',
            'fraud_status',
            'status_message',
            'approval_code',
            'card_type',
            'card_number',
        ];

        foreach ($fillableMidtransFields as $field) {
            if (isset($midtransResponse[$field])) {
                $this->{$field} = $midtransResponse[$field];
            }
        }

        // Handle timestamps
        if (isset($midtransResponse['transaction_time'])) {
            $this->transaction_time = Carbon::parse($midtransResponse['transaction_time']);
        }

        if (isset($midtransResponse['settlement_time'])) {
            $this->settlement_time = Carbon::parse($midtransResponse['settlement_time']);
            $this->paid_at = Carbon::parse($midtransResponse['settlement_time']);
        }

        if (isset($midtransResponse['expiry_time'])) {
            $this->expiry_time = Carbon::parse($midtransResponse['expiry_time']);
        }

        return $this->save();
    }

    /**
     * Create activity log.
     *
     * @param string $action
     * @param array $context
     * @return void
     */
    public function logActivity(string $action, array $context = []): void
    {
        PaymentLog::create([
            'transaction_id' => $this->transaction_id,
            'payment_transaction_id' => $this->id,
            'user_id' => $this->user_id,
            'pesanan_id' => $this->pesanan_id,
            'log_type' => 'info',
            'action' => $action,
            'message' => "Transaction {$action}",
            'request_data' => json_encode($context),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'logged_at' => now(),
        ]);
    }
}