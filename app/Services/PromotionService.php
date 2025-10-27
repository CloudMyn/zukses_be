<?php

namespace App\Services;

use App\Models\Promosi;
use App\Models\RiwayatPenggunaanPromosi;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class PromotionService
{
    /**
     * Validate promotion code against cart items
     */
    public function validatePromotionForCart($kodePromosi, $cartId = null)
    {
        $promosi = Promosi::where('kode_promosi', $kodePromosi)
            ->first();

        if (!$promosi) {
            return [
                'valid' => false,
                'message' => 'Kode promosi tidak valid',
                'error_code' => 'INVALID_CODE'
            ];
        }

        // Check if promotion is active
        if (!$promosi->isActive()) {
            return [
                'valid' => false,
                'message' => 'Promosi tidak aktif atau sudah kadaluarsa',
                'error_code' => 'INACTIVE_PROMOTION'
            ];
        }

        // Get cart to calculate total
        $cart = $cartId ? Cart::find($cartId) : null;
        if (!$cart && Auth::check()) {
            $cart = Cart::where('id_user', Auth::id())->with('items.product')->first();
        }

        if (!$cart) {
            return [
                'valid' => false,
                'message' => 'Keranjang tidak ditemukan',
                'error_code' => 'CART_NOT_FOUND'
            ];
        }

        $totalCart = $cart->items->sum(function($item) {
            return $item->quantity * $item->price;
        });

        // Check minimum purchase requirement
        if ($totalCart < $promosi->minimum_pembelian) {
            return [
                'valid' => false,
                'message' => 'Total pembelian tidak memenuhi syarat minimum untuk promosi ini',
                'error_code' => 'MIN_PURCHASE_NOT_MET',
                'minimum_pembelian' => $promosi->minimum_pembelian
            ];
        }

        // Check user has exceeded maximum usage per user
        if ($promosi->jumlah_maksimum_penggunaan_per_pengguna > 0 && Auth::check()) {
            $userUsageId = RiwayatPenggunaanPromosi::where('id_promosi', $promosi->id)
                ->where('id_pengguna', Auth::id())
                ->count();

            if ($userUsageId >= $promosi->jumlah_maksimum_penggunaan_per_pengguna) {
                return [
                    'valid' => false,
                    'message' => 'Anda telah melebihi batas penggunaan promosi ini',
                    'error_code' => 'MAX_USAGE_EXCEEDED'
                ];
            }
        }

        // Calculate discount
        $diskon = $promosi->hitungDiskon($totalCart);
        $diskon = min($diskon, $totalCart); // Ensure discount doesn't exceed total

        return [
            'valid' => true,
            'promosi' => $promosi,
            'diskon_diterapkan' => $diskon,
            'total_pembayaran_setelah_diskon' => $totalCart - $diskon
        ];
    }

    /**
     * Apply promotion to cart
     */
    public function applyPromotionToCart($kodePromosi, $cartId = null)
    {
        $validation = $this->validatePromotionForCart($kodePromosi, $cartId);

        if (!$validation['valid']) {
            return $validation;
        }

        // Update cart with promotion
        $cart = $cartId ? Cart::find($cartId) : Cart::where('id_user', Auth::id())->first();
        
        if ($cart) {
            $cart->update([
                'kode_promosi' => $kodePromosi,
                'diskon_promosi' => $validation['diskon_diterapkan']
            ]);
        }

        return $validation;
    }

    /**
     * Remove promotion from cart
     */
    public function removePromotionFromCart($cartId = null)
    {
        $cart = $cartId ? Cart::find($cartId) : Cart::where('id_user', Auth::id())->first();

        if ($cart) {
            $cart->update([
                'kode_promosi' => null,
                'diskon_promosi' => 0
            ]);
        }

        return ['success' => true, 'message' => 'Promosi berhasil dihapus dari keranjang'];
    }

    /**
     * Calculate discount for specific products if needed
     */
    public function validatePromotionForSpecificProducts($kodePromosi, $productIds)
    {
        $promosi = Promosi::where('kode_promosi', $kodePromosi)
            ->where('status_aktif', true)
            ->first();

        if (!$promosi) {
            return [
                'valid' => false,
                'message' => 'Kode promosi tidak valid',
                'error_code' => 'INVALID_CODE'
            ];
        }

        // Check if promotion is applicable to specific products
        if ($promosi->jenis_promosi === 'KELOMPOK_PRODUK') {
            $validProducts = $promosi->produk()->whereIn('tb_produk.id', $productIds)->get();
            $validProductIds = $validProducts->pluck('id')->toArray();

            // Return only the valid products
            $invalidProducts = array_diff($productIds, $validProductIds);

            if (!empty($invalidProducts)) {
                return [
                    'valid' => false,
                    'message' => 'Beberapa produk tidak berlaku untuk promosi ini',
                    'error_code' => 'PRODUCT_NOT_ELIGIBLE',
                    'invalid_products' => $invalidProducts
                ];
            }
        }

        return ['valid' => true, 'promosi' => $promosi];
    }

    /**
     * Validate promotion during checkout
     */
    public function validatePromotionAtCheckout($kodePromosi, $orderItems, $totalAmount)
    {
        $promosi = Promosi::where('kode_promosi', $kodePromosi)
            ->first();

        if (!$promosi) {
            return [
                'valid' => false,
                'message' => 'Kode promosi tidak valid',
                'error_code' => 'INVALID_CODE'
            ];
        }

        // Check if promotion is active
        if (!$promosi->isActive()) {
            return [
                'valid' => false,
                'message' => 'Promosi tidak aktif atau sudah kadaluarsa',
                'error_code' => 'INACTIVE_PROMOTION'
            ];
        }

        // Check minimum purchase requirement
        if ($totalAmount < $promosi->minimum_pembelian) {
            return [
                'valid' => false,
                'message' => 'Total pembelian tidak memenuhi syarat minimum untuk promosi ini',
                'error_code' => 'MIN_PURCHASE_NOT_MET',
                'minimum_pembelian' => $promosi->minimum_pembelian
            ];
        }

        // Check user has exceeded maximum usage per user
        if ($promosi->jumlah_maksimum_penggunaan_per_pengguna > 0 && Auth::check()) {
            $userUsageId = RiwayatPenggunaanPromosi::where('id_promosi', $promosi->id)
                ->where('id_pengguna', Auth::id())
                ->count();

            if ($userUsageId >= $promosi->jumlah_maksimum_penggunaan_per_pengguna) {
                return [
                    'valid' => false,
                    'message' => 'Anda telah melebihi batas penggunaan promosi ini',
                    'error_code' => 'MAX_USAGE_EXCEEDED'
                ];
            }
        }

        // Check if promotion is applicable to specific products
        if ($promosi->jenis_promosi === 'KELOMPOK_PRODUK' && !empty($orderItems)) {
            $productIds = collect($orderItems)->pluck('id_produk')->toArray();
            $validProducts = $promosi->produk()->whereIn('tb_produk.id', $productIds)->get();
            $validProductIds = $validProducts->pluck('id')->toArray();

            $invalidProducts = array_diff($productIds, $validProductIds);
            if (!empty($invalidProducts)) {
                return [
                    'valid' => false,
                    'message' => 'Beberapa produk dalam pesanan tidak berlaku untuk promosi ini',
                    'error_code' => 'PRODUCT_NOT_ELIGIBLE',
                    'invalid_products' => $invalidProducts
                ];
            }
        }

        // Calculate discount
        $diskon = $promosi->hitungDiskon($totalAmount);
        $diskon = min($diskon, $totalAmount); // Ensure discount doesn't exceed total

        return [
            'valid' => true,
            'promosi' => $promosi,
            'diskon_diterapkan' => $diskon,
            'total_pembayaran_setelah_diskon' => $totalAmount - $diskon
        ];
    }

    /**
     * Apply discount to order
     */
    public function applyDiscountToOrder($orderId, $kodePromosi)
    {
        $order = \App\Models\Order::find($orderId);
        if (!$order) {
            return [
                'success' => false,
                'message' => 'Pesanan tidak ditemukan',
                'error_code' => 'ORDER_NOT_FOUND'
            ];
        }

        $validation = $this->validatePromotionAtCheckout($kodePromosi, $order->items, $order->subtotal_produk);
        
        if (!$validation['valid']) {
            return $validation;
        }

        $promosi = $validation['promosi'];
        $diskon = $validation['diskon_diterapkan'];

        // Update order with promotion details using direct DB update
        \DB::table('tb_pesanan')
            ->where('id', $order->id)
            ->update([
                'kode_promosi' => $kodePromosi,
                'total_diskon_produk' => $order->total_diskon_produk + $diskon,
                'total_pembayaran' => $order->total_pembayaran - $diskon,
            ]);

        // Increment promotion usage count
        $promosi->increment('jumlah_penggunaan_saat_ini');

        return [
            'success' => true,
            'message' => 'Diskon berhasil diterapkan ke pesanan',
            'promosi' => $promosi,
            'diskon_diterapkan' => $diskon,
            'order' => $order
        ];
    }

    /**
     * Record promotion usage history
     */
    public function recordPromotionUsage($promosiId, $userId, $orderId = null, $discountAmount)
    {
        $usage = RiwayatPenggunaanPromosi::create([
            'id_promosi' => $promosiId,
            'id_pengguna' => $userId,
            'id_pesanan' => $orderId,
            'tanggal_penggunaan' => now(),
            'jumlah_diskon_diterapkan' => $discountAmount,
            'dibuat_pada' => now(),
        ]);

        return $usage;
    }

    /**
     * Complete the promotion usage process (validate, apply to order, record history)
     */
    public function completePromotionUsage($kodePromosi, $orderId, $userId)
    {
        $order = \App\Models\Order::find($orderId);
        if (!$order) {
            return [
                'success' => false,
                'message' => 'Pesanan tidak ditemukan',
                'error_code' => 'ORDER_NOT_FOUND'
            ];
        }

        $validation = $this->validatePromotionAtCheckout($kodePromosi, $order->items, $order->subtotal_produk);
        
        if (!$validation['valid']) {
            return $validation;
        }

        $applyResult = $this->applyDiscountToOrder($orderId, $kodePromosi);
        
        if (!$applyResult['success']) {
            return $applyResult;
        }

        // Record the usage in history
        $this->recordPromotionUsage(
            $applyResult['promosi']->id,
            $userId,
            $orderId,
            $applyResult['diskon_diterapkan']
        );

        return [
            'success' => true,
            'message' => 'Promosi berhasil diterapkan dan dicatat',
            'promosi' => $applyResult['promosi'],
            'diskon_diterapkan' => $applyResult['diskon_diterapkan'],
            'order' => $applyResult['order']
        ];
    }
}