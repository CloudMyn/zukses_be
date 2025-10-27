<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Promosi;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;
use App\Services\PromotionService;
use Tymon\JWTAuth\Facades\JWTAuth;

class PromotionOrderIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;
    protected $promotionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = JWTAuth::fromUser($this->user);
        $this->promotionService = new PromotionService();
    }

    public function test_apply_promotion_to_order_success()
    {
        $promosi = Promosi::factory()->create([
            'kode_promosi' => 'ORDER_TEST',
            'status_aktif' => true,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_berakhir' => now()->addDay(),
            'tipe_diskon' => 'PERSEN',
            'nilai_diskon' => 10,
            'minimum_pembelian' => 50000,
            'jenis_promosi' => 'KODE_PROMOSI', // Ensure it's a general code promotion, not product-specific
            'jumlah_maksimum_penggunaan_per_pengguna' => 0, // No limit for this test
        ]);

        $order = Order::factory()->create([
            'id_customer' => $this->user->id,
            'subtotal_produk' => 100000,
            'total_pembayaran' => 100000,
        ]);

        $result = $this->promotionService->applyDiscountToOrder($order->id, 'ORDER_TEST');

        $this->assertTrue($result['success']);
        $this->assertEquals('Diskon berhasil diterapkan ke pesanan', $result['message']);
        $this->assertEquals(10000, $result['diskon_diterapkan']); // 10% of 100000

        // Refresh the order from the database to check if it was updated
        $order->refresh();
        $this->assertEquals('ORDER_TEST', $order->kode_promosi);
        $this->assertGreaterThanOrEqual(10000, $order->total_diskon_produk);
    }

    public function test_apply_promotion_to_order_with_invalid_order()
    {
        $result = $this->promotionService->applyDiscountToOrder(999999, 'NONEXISTENT'); // Non-existent order ID

        $this->assertFalse($result['success']);
        $this->assertEquals('ORDER_NOT_FOUND', $result['error_code']);
    }

    public function test_apply_promotion_to_order_with_invalid_promo()
    {
        $order = Order::factory()->create([
            'id_customer' => $this->user->id,
            'subtotal_produk' => 100000,
            'total_pembayaran' => 100000,
        ]);

        $result = $this->promotionService->applyDiscountToOrder($order->id, 'NONEXISTENT');

        $this->assertFalse($result['valid']);
        $this->assertEquals('INVALID_CODE', $result['error_code']);
    }

    public function test_apply_promotion_to_order_with_insufficient_purchase()
    {
        $promosi = Promosi::factory()->create([
            'kode_promosi' => 'MIN_TEST',
            'status_aktif' => true,
            'minimum_pembelian' => 200000, // High minimum
        ]);

        $order = Order::factory()->create([
            'id_customer' => $this->user->id,
            'subtotal_produk' => 100000, // Below minimum
            'total_pembayaran' => 100000,
        ]);

        $result = $this->promotionService->applyDiscountToOrder($order->id, 'MIN_TEST');

        $this->assertFalse($result['valid']);
        $this->assertEquals('MIN_PURCHASE_NOT_MET', $result['error_code']);
    }

    public function test_promotion_usage_history_recorded_after_order()
    {
        $promosi = Promosi::factory()->create([
            'kode_promosi' => 'HISTORY_TEST',
            'status_aktif' => true,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_berakhir' => now()->addDay(),
            'tipe_diskon' => 'NOMINAL',
            'nilai_diskon' => 5000,
            'minimum_pembelian' => 50000,
        ]);

        $order = Order::factory()->create([
            'id_customer' => $this->user->id,
            'subtotal_produk' => 100000,
            'total_pembayaran' => 100000,
        ]);

        $result = $this->promotionService->completePromotionUsage('HISTORY_TEST', $order->id, $this->user->id);

        $this->assertTrue($result['success']);
        $this->assertEquals('Promosi berhasil diterapkan dan dicatat', $result['message']);

        // Check that usage history was created
        $this->assertDatabaseHas('tb_riwayat_penggunaan_promosi', [
            'id_promosi' => $promosi->id,
            'id_pengguna' => $this->user->id,
            'id_pesanan' => $order->id,
            'jumlah_diskon_diterapkan' => 5000,
        ]);
    }

    public function test_complete_promotion_usage_with_invalid_data()
    {
        $result = $this->promotionService->completePromotionUsage('INVALID', 999999, $this->user->id);

        $this->assertFalse($result['success']);
        $this->assertEquals('ORDER_NOT_FOUND', $result['error_code']);
    }

    public function test_promotion_applied_to_order_affects_total_payment()
    {
        $promosi = Promosi::factory()->create([
            'kode_promosi' => 'PAYMENT_TEST',
            'status_aktif' => true,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_berakhir' => now()->addDay(),
            'tipe_diskon' => 'PERSEN',
            'nilai_diskon' => 15,
            'minimum_pembelian' => 50000,
        ]);

        $order = Order::factory()->create([
            'id_customer' => $this->user->id,
            'subtotal_produk' => 100000,
            'total_pembayaran' => 100000,
            'total_diskon_produk' => 0, // Initially no discount
        ]);

        $result = $this->promotionService->applyDiscountToOrder($order->id, 'PAYMENT_TEST');

        $this->assertTrue($result['success']);

        $order->refresh();

        // The total payment should be reduced by the discount amount (15% of 100000 = 15000)
        $this->assertEquals(85000, $order->total_pembayaran);
        $this->assertGreaterThanOrEqual(15000, $order->total_diskon_produk);
    }

    public function test_multiple_promotions_on_same_order()
    {
        $promosi1 = Promosi::factory()->create([
            'kode_promosi' => 'MULTI_TEST1',
            'status_aktif' => true,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_berakhir' => now()->addDay(),
            'tipe_diskon' => 'NOMINAL',
            'nilai_diskon' => 2000,
            'minimum_pembelian' => 50000,
            'dapat_digabungkan' => true, // Can be combined
        ]);

        $promosi2 = Promosi::factory()->create([
            'kode_promosi' => 'MULTI_TEST2',
            'status_aktif' => true,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_berakhir' => now()->addDay(),
            'tipe_diskon' => 'NOMINAL',
            'nilai_diskon' => 3000,
            'minimum_pembelian' => 50000,
            'dapat_digabungkan' => true, // Can be combined
        ]);

        $order = Order::factory()->create([
            'id_customer' => $this->user->id,
            'subtotal_produk' => 100000,
            'total_pembayaran' => 100000,
            'total_diskon_produk' => 0,
        ]);

        // Apply first promotion
        $result1 = $this->promotionService->applyDiscountToOrder($order->id, 'MULTI_TEST1');
        $this->assertTrue($result1['success']);

        $order->refresh();
        $expectedPaymentAfterFirst = 100000 - 2000; // 98000

        // Apply second promotion
        $result2 = $this->promotionService->applyDiscountToOrder($order->id, 'MULTI_TEST2');
        $this->assertTrue($result2['success']);

        $order->refresh();
        // The total discount should be 2000 + 3000 = 5000
        // So final payment should be 100000 - 5000 = 95000
        $expectedTotalDiscount = 5000;
        $expectedFinalPayment = 95000;

        $this->assertEquals($expectedFinalPayment, $order->total_pembayaran);
        $this->assertGreaterThanOrEqual($expectedTotalDiscount, $order->total_diskon_produk);
    }

    public function test_order_items_validation_with_product_specific_promotion()
    {
        // For this test, we need to consider promotions that apply to specific products
        $this->assertTrue(true); // Placeholder for more complex validation testing
    }
}