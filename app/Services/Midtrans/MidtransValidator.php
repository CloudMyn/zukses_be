<?php

namespace App\Services\Midtrans;

use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

/**
 * MidtransValidator
 *
 * Validator untuk data transaksi Midtrans
 * Memastikan data yang dikirim ke Midtrans valid dan aman
 *
 * @author Zukses Development Team
 * @version 1.0.0
 */
class MidtransValidator
{
    /**
     * Validasi data transaksi sebelum dikirim ke Midtrans
     *
     * @param array $data Data transaksi
     * @return void
     * @throws Exception
     */
    public function validateTransactionData(array $data): void
    {
        try {
            // Main validation rules
            $rules = [
                'transaction_id' => 'required|string|max:100',
                'order_id' => 'required|string|max:100',
                'gross_amount' => 'required|numeric|min:1000|max:100000000',
                'currency' => 'required|string|size:3|in:IDR',
                'payment_type' => 'nullable|string|in:credit_card,bank_transfer,echannel,permata_va,bca_va,bni_va,bri_va,cimb_va,other_va,gopay,shopeepay,qris',
                'transaction_details' => 'required|array',
                'customer_details' => 'required|array',
                'item_details' => 'required|array|min:1|max:100',
                'expiry_time' => 'nullable|date_format:Y-m-d H:i:s|after:now',
            ];

            // Custom error messages
            $messages = [
                'transaction_id.required' => 'Transaction ID harus diisi',
                'transaction_id.max' => 'Transaction ID maksimal 100 karakter',
                'order_id.required' => 'Order ID harus diisi',
                'order_id.max' => 'Order ID maksimal 100 karakter',
                'gross_amount.required' => 'Gross amount harus diisi',
                'gross_amount.numeric' => 'Gross amount harus berupa angka',
                'gross_amount.min' => 'Gross amount minimal Rp 1.000',
                'gross_amount.max' => 'Gross amount maksimal Rp 100.000.000',
                'currency.required' => 'Currency harus diisi',
                'currency.size' => 'Currency harus 3 karakter (IDR)',
                'currency.in' => 'Currency hanya mendukung IDR',
                'transaction_details.required' => 'Transaction details harus diisi',
                'customer_details.required' => 'Customer details harus diisi',
                'item_details.required' => 'Item details harus diisi',
                'item_details.min' => 'Minimal harus ada 1 item',
                'item_details.max' => 'Maksimal 100 item',
                'expiry_time.after' => 'Expiry time harus di masa depan',
            ];

            // Validate main data
            $validator = Validator::make($data, $rules, $messages);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                Log::error('MidtransValidator: Validasi data transaksi gagal', [
                    'errors' => $errors,
                    'data' => $this->sanitizeLogData($data)
                ]);
                throw new Exception('Validasi gagal: ' . implode(', ', $errors));
            }

            // Validate nested data
            $this->validateTransactionDetails($data['transaction_details']);
            $this->validateCustomerDetails($data['customer_details']);
            $this->validateItemDetails($data['item_details']);

            // Additional validation based on payment type
            if (isset($data['payment_type'])) {
                $this->validatePaymentTypeSpecific($data);
            }

            // Security validations
            $this->validateForSecurity($data);

            Log::info('MidtransValidator: Validasi data transaksi berhasil', [
                'transaction_id' => $data['transaction_id'],
                'order_id' => $data['order_id'],
                'amount' => $data['gross_amount']
            ]);

        } catch (Exception $e) {
            Log::error('MidtransValidator: Error saat validasi transaksi', [
                'transaction_id' => $data['transaction_id'] ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Validasi transaction details
     *
     * @param array $details
     * @return void
     * @throws Exception
     */
    private function validateTransactionDetails(array $details): void
    {
        $rules = [
            'order_id' => 'required|string|max:100',
            'gross_amount' => 'required|numeric|min:1000|max:100000000',
        ];

        $validator = Validator::make($details, $rules);
        if ($validator->fails()) {
            throw new Exception('Transaction details invalid: ' . implode(', ', $validator->errors()->all()));
        }
    }

    /**
     * Validasi customer details
     *
     * @param array $customer
     * @return void
     * @throws Exception
     */
    private function validateCustomerDetails(array $customer): void
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|regex:/^[0-9+\-\s()]+$/|min:10|max:20',
            'billing_address' => 'nullable|array',
            'shipping_address' => 'nullable|array',
        ];

        $messages = [
            'first_name.required' => 'Nama depan harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'phone.required' => 'Nomor telepon harus diisi',
            'phone.regex' => 'Format nomor telepon tidak valid',
            'phone.min' => 'Nomor telepon minimal 10 digit',
        ];

        $validator = Validator::make($customer, $rules, $messages);
        if ($validator->fails()) {
            throw new Exception('Customer details invalid: ' . implode(', ', $validator->errors()->all()));
        }

        // Validate addresses if present
        if (isset($customer['billing_address'])) {
            $this->validateAddress($customer['billing_address'], 'billing');
        }

        if (isset($customer['shipping_address'])) {
            $this->validateAddress($customer['shipping_address'], 'shipping');
        }
    }

    /**
     * Validasi alamat
     *
     * @param array $address
     * @param string $type
     * @return void
     * @throws Exception
     */
    private function validateAddress(array $address, string $type): void
    {
        $rules = [
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'country_code' => 'required|string|size:2|in:ID',
        ];

        $messages = [
            'address.required' => "Alamat {$type} harus diisi",
            'city.required' => "Kota {$type} harus diisi",
            'postal_code.required' => "Kode pos {$type} harus diisi",
            'country_code.required' => "Negara {$type} harus diisi",
            'country_code.size' => "Kode negara {$type} harus 2 karakter",
            'country_code.in' => "Hanya mendukung negara Indonesia (ID)",
        ];

        $validator = Validator::make($address, $rules, $messages);
        if ($validator->fails()) {
            throw new Exception("{$type} address invalid: " . implode(', ', $validator->errors()->all()));
        }
    }

    /**
     * Validasi item details
     *
     * @param array $items
     * @return void
     * @throws Exception
     */
    private function validateItemDetails(array $items): void
    {
        $totalAmount = 0;

        foreach ($items as $index => $item) {
            $rules = [
                'id' => 'required|string|max:100',
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:1|max:100000000',
                'quantity' => 'required|integer|min:1|max:1000',
                'category' => 'nullable|string|max:100',
                'merchant_name' => 'nullable|string|max:255',
                'url' => 'nullable|url|max:500',
                'image_url' => 'nullable|url|max:500',
            ];

            $messages = [
                "id.required" => "Item ID pada index {$index} harus diisi",
                "name.required" => "Item name pada index {$index} harus diisi",
                "price.required" => "Price pada index {$index} harus diisi",
                "price.numeric" => "Price pada index {$index} harus angka",
                "price.min" => "Price pada index {$index} minimal 1",
                "quantity.required" => "Quantity pada index {$index} harus diisi",
                "quantity.integer" => "Quantity pada index {$index} harus angka",
                "quantity.min" => "Quantity pada index {$index} minimal 1",
                "quantity.max" => "Quantity pada index {$index} maksimal 1000",
            ];

            $validator = Validator::make($item, $rules, $messages);
            if ($validator->fails()) {
                throw new Exception('Item details invalid at index ' . $index . ': ' . implode(', ', $validator->errors()->all()));
            }

            // Calculate total amount
            $totalAmount += ($item['price'] * $item['quantity']);

            // Security checks for item data
            $this->validateItemForSecurity($item, $index);
        }

        // Check if total amount matches gross_amount
        if (isset($items[0]['_parent_gross_amount'])) {
            if (abs($totalAmount - $items[0]['_parent_gross_amount']) > 0.01) {
                throw new Exception('Total item amount does not match gross amount');
            }
        }
    }

    /**
     * Validasi spesifik berdasarkan payment type
     *
     * @param array $data
     * @return void
     * @throws Exception
     */
    private function validatePaymentTypeSpecific(array $data): void
    {
        switch ($data['payment_type']) {
            case 'credit_card':
                $this->validateCreditCardPayment($data);
                break;

            case 'bank_transfer':
                $this->validateBankTransferPayment($data);
                break;

            case 'echannel':
                $this->validateEchannelPayment($data);
                break;

            case 'gopay':
            case 'shopeepay':
                $this->validateEwalletPayment($data);
                break;
        }
    }

    /**
     * Validasi pembayaran kartu kredit
     *
     * @param array $data
     * @return void
     * @throws Exception
     */
    private function validateCreditCardPayment(array $data): void
    {
        if (isset($data['credit_card'])) {
            $cc = $data['credit_card'];

            $rules = [
                'token_id' => 'required_if:credit_card.save_token,true|string|max:100',
                'save_token' => 'nullable|boolean',
                'authentication' => 'nullable|boolean',
                'bank' => 'nullable|string|in:bni,mandiri,cimb,bca,other',
                'type' => 'nullable|in:authorize,capture',
                'installment_term' => 'nullable|integer|min:0|max:48',
            ];

            $validator = Validator::make($cc, $rules);
            if ($validator->fails()) {
                throw new Exception('Credit card details invalid: ' . implode(', ', $validator->errors()->all()));
            }
        }
    }

    /**
     * Validasi pembayaran bank transfer
     *
     * @param array $data
     * @return void
     * @throws Exception
     */
    private function validateBankTransferPayment(array $data): void
    {
        if (isset($data['bank_transfer'])) {
            $bt = $data['bank_transfer'];

            $rules = [
                'bank' => 'required|string|in:bca,bni,bri,mandiri,cimb,permata,other',
                'va_number' => 'nullable|string|max:20|regex:/^[0-9]+$/',
                'free_text' => 'nullable|string|max:100',
            ];

            $validator = Validator::make($bt, $rules);
            if ($validator->fails()) {
                throw new Exception('Bank transfer details invalid: ' . implode(', ', $validator->errors()->all()));
            }
        }
    }

    /**
     * Validasi pembayaran e-channel
     *
     * @param array $data
     * @return void
     * @throws Exception
     */
    private function validateEchannelPayment(array $data): void
    {
        if (isset($data['echannel'])) {
            $echannel = $data['echannel'];

            $rules = [
                'bill_info1' => 'required|string|max:30',
                'bill_info2' => 'nullable|string|max:30',
                'bill_info3' => 'nullable|string|max:30',
                'bill_info4' => 'nullable|string|max:30',
                'bill_info5' => 'nullable|string|max:30',
            ];

            $validator = Validator::make($echannel, $rules);
            if ($validator->fails()) {
                throw new Exception('E-channel details invalid: ' . implode(', ', $validator->errors()->all()));
            }
        }
    }

    /**
     * Validasi pembayaran e-wallet
     *
     * @param array $data
     * @return void
     * @throws Exception
     */
    private function validateEwalletPayment(array $data): void
    {
        // E-wallet payments typically don't require additional validation
        // but we can add custom validation if needed
    }

    /**
     * Validasi keamanan untuk data transaksi
     *
     * @param array $data
     * @return void
     * @throws Exception
     */
    private function validateForSecurity(array $data): void
    {
        // Check for suspicious patterns
        $suspiciousPatterns = [
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
            '/javascript:/i',
            '/on\w+\s*=/i',
        ];

        $checkFields = [
            'transaction_id', 'order_id',
            'customer_details.first_name', 'customer_details.last_name',
            'customer_details.email'
        ];

        foreach ($checkFields as $field) {
            $value = $this->getNestedValue($data, $field);
            if ($value && is_string($value)) {
                foreach ($suspiciousPatterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        Log::warning('MidtransValidator: Suspicious pattern detected', [
                            'field' => $field,
                            'value' => substr($value, 0, 50)
                        ]);
                        throw new Exception('Data contains suspicious content');
                    }
                }
            }
        }

        // Check for reasonable amounts
        if (isset($data['gross_amount'])) {
            if ($data['gross_amount'] > 100000000) { // 100 juta
                Log::warning('MidtransValidator: Large transaction amount detected', [
                    'amount' => $data['gross_amount'],
                    'transaction_id' => $data['transaction_id'] ?? 'unknown'
                ]);
            }
        }
    }

    /**
     * Validasi keamanan untuk item
     *
     * @param array $item
     * @param int $index
     * @return void
     * @throws Exception
     */
    private function validateItemForSecurity(array $item, int $index): void
    {
        // Check for suspicious patterns in item name
        $suspiciousPatterns = [
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
            '/javascript:/i',
            '/on\w+\s*=/i',
        ];

        $checkFields = ['name', 'category', 'merchant_name'];

        foreach ($checkFields as $field) {
            if (isset($item[$field]) && is_string($item[$field])) {
                foreach ($suspiciousPatterns as $pattern) {
                    if (preg_match($pattern, $item[$field])) {
                        throw new Exception("Item {$field} at index {$index} contains suspicious content");
                    }
                }
            }
        }

        // Validate URLs if present
        if (isset($item['url']) && !$this->isValidUrl($item['url'])) {
            throw new Exception("Item URL at index {$index} is not valid");
        }

        if (isset($item['image_url']) && !$this->isValidUrl($item['image_url'])) {
            throw new Exception("Item image URL at index {$index} is not valid");
        }
    }

    /**
     * Get nested value from array using dot notation
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
     * Validate URL
     *
     * @param string $url
     * @return bool
     */
    private function isValidUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Sanitize data for logging
     *
     * @param array $data
     * @return array
     */
    public function sanitizeLogData(array $data): array
    {
        $sensitiveKeys = ['credit_card', 'token_id', 'save_token'];

        foreach ($sensitiveKeys as $key) {
            if (isset($data[$key])) {
                $data[$key] = '[FILTERED]';
            }
        }

        // Filter customer sensitive data
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

        return $data;
    }

    /**
     * Mask email for logging
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
     * Mask phone number for logging
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
}