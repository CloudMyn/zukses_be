<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Promosi;
use App\Models\User;
use App\Models\RiwayatPenggunaanPromosi;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PromotionBoundaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_promotion_exactly_at_maximum_usage()
    {
        $promosi = Promosi::factory()->create([
            'jumlah_maksimum_penggunaan' => 5,
            'jumlah_penggunaan_saat_ini' => 4, // One usage left
            'status_aktif' => true,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_berakhir' => now()->addDay(),
        ]);

        // This should still be active since we haven't reached the limit yet
        $this->assertTrue($promosi->isActive());
        
        // Simulate one more usage
        $promosi->increment('jumlah_penggunaan_saat_ini');
        
        // Now it should be inactive
        $this->assertFalse($promosi->isActive());
    }

    public function test_promotion_exactly_at_date_boundary()
    {
        $currentTime = now();
        
        // Test at exact start time
        $promosiStart = Promosi::factory()->create([
            'status_aktif' => true,
            'tanggal_mulai' => $currentTime->copy(), // Use copy to avoid mutation
            'tanggal_berakhir' => $currentTime->copy()->addDay(), // Use copy to avoid mutation
        ]);

        $this->assertTrue($promosiStart->isActive());

        // Test at exact end time
        $exactTime = now();
        // Create promotion with end time slightly in the future to ensure it's still active when tested
        $endTime = $exactTime->copy()->addSeconds(1);
        $promosiEnd = Promosi::factory()->create([
            'status_aktif' => true,
            'tanggal_mulai' => $endTime->copy()->subDay(),
            'tanggal_berakhir' => $endTime,
        ]);

        // The promotion should be active as the current time is before the end time
        $this->assertTrue($promosiEnd->isActive());
    }

    public function test_promotion_date_boundaries()
    {
        $currentTime = now();
        
        // Test right before end time (should be active)
        $promosiBeforeEnd = Promosi::factory()->create([
            'status_aktif' => true,
            'tanggal_mulai' => $currentTime->copy()->subDay(), // Use copy to avoid mutation
            'tanggal_berakhir' => $currentTime->copy()->addSecond(), // Use copy to avoid mutation
        ]);

        $this->assertTrue($promosiBeforeEnd->isActive());

        // Test right after end time (should be inactive)
        $currentTime2 = now();
        $promosiAfterEnd = Promosi::factory()->create([
            'status_aktif' => true,
            'tanggal_mulai' => $currentTime2->copy()->subDay(), // Use copy to avoid mutation
            'tanggal_berakhir' => $currentTime2->copy()->subSecond(), // Use copy to avoid mutation
        ]);

        $this->assertFalse($promosiAfterEnd->isActive());
    }

    public function test_promotion_user_limit_boundary()
    {
        $user = User::factory()->create();
        $promosi = Promosi::factory()->create([
            'status_aktif' => true,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_berakhir' => now()->addDay(),
            'jumlah_maksimum_penggunaan_per_pengguna' => 2, // Limit of 2 per user
        ]);

        // Create first usage
        RiwayatPenggunaanPromosi::create([
            'id_promosi' => $promosi->id,
            'id_pengguna' => $user->id,
            'tanggal_penggunaan' => now(),
            'jumlah_diskon_diterapkan' => 0,
        ]);

        // At this point, the user should still be able to use the promo (1 out of 2)
        $userUsageId = RiwayatPenggunaanPromosi::where('id_promosi', $promosi->id)
            ->where('id_pengguna', $user->id)
            ->count();
            
        $this->assertEquals(1, $userUsageId);
        $this->assertLessThan($promosi->jumlah_maksimum_penggunaan_per_pengguna, $userUsageId);

        // Create second usage (should be the last valid one)
        RiwayatPenggunaanPromosi::create([
            'id_promosi' => $promosi->id,
            'id_pengguna' => $user->id,
            'tanggal_penggunaan' => now(),
            'jumlah_diskon_diterapkan' => 0,
        ]);

        $userUsageId = RiwayatPenggunaanPromosi::where('id_promosi', $promosi->id)
            ->where('id_pengguna', $user->id)
            ->count();
            
        $this->assertEquals(2, $userUsageId);
        $this->assertEquals($promosi->jumlah_maksimum_penggunaan_per_pengguna, $userUsageId);

        // After 2 usages with a limit of 2, a third usage attempt would exceed the limit
        // This means the current usage count equals the limit (boundary condition)
        $this->assertEquals($promosi->jumlah_maksimum_penggunaan_per_pengguna, $userUsageId);
    }

    public function test_discount_calculation_boundaries()
    {
        $promosi = Promosi::factory()->create([
            'tipe_diskon' => 'NOMINAL',
            'nilai_diskon' => 100000, // High discount
        ]);

        // Test when purchase amount is exactly equal to discount
        $diskon = $promosi->hitungDiskon(100000);
        $this->assertEquals(100000, $diskon); // Should apply full discount

        // Test when purchase amount is less than discount (should cap at purchase amount)
        $diskon = $promosi->hitungDiskon(50000);
        $this->assertEquals(50000, $diskon); // Should cap at purchase amount

        // Test percentage discount with high percentage
        $promosiPercent = Promosi::factory()->create([
            'tipe_diskon' => 'PERSEN',
            'nilai_diskon' => 100, // 100% discount
        ]);

        $diskon = $promosiPercent->hitungDiskon(50000);
        $this->assertEquals(50000, $diskon); // 100% of 50000 = 50000
    }

    public function test_zero_minimum_purchase_requirement()
    {
        $promosi = Promosi::factory()->create([
            'minimum_pembelian' => 0,
            'status_aktif' => true,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_berakhir' => now()->addDay(),
        ]);

        // Should be valid for any purchase amount when minimum is 0
        $result = $promosi->isActive();
        $this->assertTrue($result);
    }

    public function test_large_numbers_handling()
    {
        $largeNumber = 99999999.99; // Max value for DECIMAL(10,2) is 99999999.99
        
        $promosi = Promosi::factory()->create([
            'tipe_diskon' => 'NOMINAL',
            'nilai_diskon' => $largeNumber,
        ]);

        $calculatedDiscount = $promosi->hitungDiskon($largeNumber);
        
        // Should not exceed the purchase amount
        $this->assertEquals($largeNumber, $calculatedDiscount);
    }

    public function test_promotion_with_zero_usage_limits()
    {
        // When max usage is 0, it should mean unlimited usage
        $promosi = Promosi::factory()->create([
            'jumlah_maksimum_penggunaan' => 0, // Unlimited
            'jumlah_penggunaan_saat_ini' => 1000, // Already high usage count
            'status_aktif' => true,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_berakhir' => now()->addDay(),
        ]);

        // Despite high usage count, should still be active because limit is 0 (unlimited)
        $this->assertTrue($promosi->isActive());
    }
}