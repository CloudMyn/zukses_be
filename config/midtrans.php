<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Midtrans Payment Gateway
    |--------------------------------------------------------------------------
    |
    | File konfigurasi untuk mengatur koneksi dan pengaturan Midtrans
    | payment gateway. Pastikan untuk mengisi kredensial yang benar
    | sesuai dengan environment yang digunakan.
    |
    */

    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'merchant_id' => env('MIDTRANS_MERCHANT_ID'),

    /**
     * Environment: sandbox atau production
     * - sandbox: untuk pengembangan dan testing
     * - production: untuk live transaksi
     */
    'environment' => env('MIDTRANS_ENVIRONMENT', 'sandbox'),

    /**
     * URL endpoints berdasarkan environment
     */
    'urls' => [
        'sandbox' => [
            'snap_url' => 'https://app.sandbox.midtrans.com/snap/v1',
            'api_url' => 'https://api.sandbox.midtrans.com/v2',
        ],
        'production' => [
            'snap_url' => 'https://app.midtrans.com/snap/v1',
            'api_url' => 'https://api.midtrans.com/v2',
        ],
    ],

    /**
     * URL untuk webhook notifications
     */
    'webhook' => [
        'payment_notification' => env('MIDTRANS_PAYMENT_NOTIFICATION_URL',
            env('APP_URL') . '/api/webhooks/midtrans/payment-notification'),
    ],

    /**
     * URL redirects setelah pembayaran
     */
    'redirects' => [
        'finish' => env('MIDTRANS_FINISH_REDIRECT_URL',
            env('APP_URL') . '/payment/success'),
        'error' => env('MIDTRANS_ERROR_REDIRECT_URL',
            env('APP_URL') . '/payment/error'),
        'pending' => env('MIDTRANS_PENDING_REDIRECT_URL',
            env('APP_URL') . '/payment/pending'),
    ],

    /**
     * Pengaturan transaksi
     */
    'transaction' => [
        // Timeout untuk pembayaran dalam menit
        'timeout_minutes' => env('MIDTRANS_TIMEOUT_MINUTES', 60),

        // Jumlah maksimal transaksi
        'max_amount' => env('MIDTRANS_MAX_AMOUNT', 100000000),

        // Jumlah minimal transaksi
        'min_amount' => env('MIDTRANS_MIN_AMOUNT', 1000),

        // Metode pembayaran yang diizinkan
        'enabled_payments' => [
            'credit_card',
            'bank_transfer',
            'echannel', // Mandiri
            'permata_va',
            'bca_va',
            'bni_va',
            'bri_va',
            'cimb_va',
            'other_va',
            'gopay',
            'shopeepay',
            'qris',
        ],

        // Metode pembayaran yang disabled
        'disabled_payments' => [],
    ],

    /**
     * Pengaturan keamanan
     */
    'security' => [
        // Enable signature validation untuk webhook
        'validate_webhook_signature' => true,

        // API key encryption
        'encrypt_api_keys' => true,

        // Rate limiting
        'rate_limiting' => [
            'enabled' => true,
            'max_attempts' => 5,
            'decay_minutes' => 1,
        ],
    ],

    /**
     * Pengaturan notifikasi
     */
    'notifications' => [
        // Enable email notifications
        'email_enabled' => true,

        // Enable SMS notifications
        'sms_enabled' => false,

        // Enable push notifications
        'push_enabled' => true,
    ],

    /**
     * Pengaturan logging
     */
    'logging' => [
        // Enable debug logging
        'debug' => env('APP_DEBUG', false),

        // Log level: debug, info, warning, error
        'level' => env('MIDTRANS_LOG_LEVEL', 'info'),

        // Log file name
        'file' => 'midtrans.log',
    ],

    /**
     * Pengaturan caching
     */
    'cache' => [
        // Cache payment status untuk mengurangi API calls
        'payment_status_ttl' => 300, // 5 menit

        // Cache SNAP token
        'snap_token_ttl' => 1800, // 30 menit
    ],

    /**
     * Pengaturan error handling
     */
    'error_handling' => [
        // Max retry attempts untuk failed API calls
        'max_retries' => 3,

        // Retry delay dalam detik
        'retry_delay' => 1,

        // Enable graceful degradation
        'graceful_degradation' => true,
    ],
];