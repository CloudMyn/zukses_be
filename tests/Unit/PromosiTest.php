<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Promosi;
use App\Models\User;
use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\RiwayatPenggunaanPromosi;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PromosiTest extends TestCase
{
    use RefreshDatabase;

    public function test_promosi_model_has_correct_fillable_fields()
    {
        $promosi = new Promosi();

        $fillable = [
            'kode_promosi',
            'nama_promosi',
            'deskripsi',
            'jenis_promosi',
            'tipe_diskon',
            'nilai_diskon',
            'jumlah_maksimum_penggunaan',
            'jumlah_penggunaan_saat_ini',
            'jumlah_maksimum_penggunaan_per_pengguna',
            'tanggal_mulai',
            'tanggal_berakhir',
            'minimum_pembelian',
            'id_kategori_produk',
            'dapat_digabungkan',
            'status_aktif',
            'id_pembuat',
            'id_pembaharu_terakhir',
        ];

        $this->assertEquals($fillable, $promosi->getFillable());
    }

    public function test_promosi_model_casts_attributes_correctly()
    {
        $promosi = new Promosi();

        $casts = [
            'tanggal_mulai' => 'datetime',
            'tanggal_berakhir' => 'datetime',
            'nilai_diskon' => 'decimal:2',
            'minimum_pembelian' => 'decimal:2',
            'jumlah_maksimum_penggunaan' => 'integer',
            'jumlah_penggunaan_saat_ini' => 'integer',
            'jumlah_maksimum_penggunaan_per_pengguna' => 'integer',
            'dapat_digabungkan' => 'boolean',
            'status_aktif' => 'boolean',
        ];

        $modelCasts = $promosi->getCasts();

        foreach ($casts as $field => $type) {
            $this->assertArrayHasKey($field, $modelCasts);
            $this->assertEquals($type, $modelCasts[$field]);
        }
    }

    public function test_promosi_belongs_to_pembuat()
    {
        $user = User::factory()->create();
        $promosi = Promosi::factory()->create(['id_pembuat' => $user->id]);

        $this->assertTrue($promosi->pembuat()->exists());
        $this->assertEquals($user->id, $promosi->pembuat->id);
    }

    public function test_promosi_belongs_to_pembaharu_terakhir()
    {
        $user = User::factory()->create();
        $promosi = Promosi::factory()->create(['id_pembaharu_terakhir' => $user->id]);

        $this->assertTrue($promosi->pembaharu()->exists());
        $this->assertEquals($user->id, $promosi->pembaharu->id);
    }

    public function test_promosi_belongs_to_kategori_produk()
    {
        $kategori = CategoryProduct::factory()->create();
        $promosi = Promosi::factory()->create(['id_kategori_produk' => $kategori->id]);

        $this->assertTrue($promosi->kategori_produk()->exists());
        $this->assertEquals($kategori->id, $promosi->kategori_produk->id);
    }

    public function test_promosi_belongs_to_many_produk()
    {
        $promosi = Promosi::factory()->create();
        $products = Product::factory(3)->create();

        foreach ($products as $product) {
            $promosi->produk()->attach($product->id);
        }

        $this->assertEquals(3, $promosi->produk()->count());
        $this->assertEquals($products->pluck('id')->toArray(), $promosi->produk->pluck('id')->toArray());
    }

    public function test_promosi_has_many_riwayat_penggunaan()
    {
        $promosi = Promosi::factory()->create();
        $riwayat = RiwayatPenggunaanPromosi::factory(3)->create(['id_promosi' => $promosi->id]);

        $this->assertEquals(3, $promosi->riwayat_penggunaan()->count());
    }

    public function test_is_active_method_returns_true_when_promo_is_active()
    {
        $promosi = Promosi::factory()->create([
            'status_aktif' => true,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_berakhir' => now()->addDay(),
            'jumlah_maksimum_penggunaan' => 10,
            'jumlah_penggunaan_saat_ini' => 5,
        ]);

        $this->assertTrue($promosi->isActive());
    }

    public function test_is_active_method_returns_false_when_not_active()
    {
        $promosi = Promosi::factory()->create([
            'status_aktif' => false,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_berakhir' => now()->addDay(),
            'jumlah_maksimum_penggunaan' => 10,
            'jumlah_penggunaan_saat_ini' => 5,
        ]);

        $this->assertFalse($promosi->isActive());
    }

    public function test_is_active_method_returns_false_when_outside_date_range()
    {
        $promosi = Promosi::factory()->create([
            'status_aktif' => true,
            'tanggal_mulai' => now()->addDay(),
            'tanggal_berakhir' => now()->addDays(2),
            'jumlah_maksimum_penggunaan' => 10,
            'jumlah_penggunaan_saat_ini' => 5,
        ]);

        $this->assertFalse($promosi->isActive());
    }

    public function test_is_active_method_returns_false_when_max_usage_reached()
    {
        $promosi = Promosi::factory()->create([
            'status_aktif' => true,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_berakhir' => now()->addDay(),
            'jumlah_maksimum_penggunaan' => 5,
            'jumlah_penggunaan_saat_ini' => 5,
        ]);

        $this->assertFalse($promosi->isActive());
    }

    public function test_hitung_diskon_method_calculates_percentage_discount()
    {
        $promosi = Promosi::factory()->create([
            'tipe_diskon' => 'PERSEN',
            'nilai_diskon' => 10, // 10%
        ]);

        $diskon = $promosi->hitungDiskon(100000); // 10% of 100,000 = 10,000

        $this->assertEquals(10000, $diskon);
    }

    public function test_hitung_diskon_method_calculates_nominal_discount()
    {
        $promosi = Promosi::factory()->create([
            'tipe_diskon' => 'NOMINAL',
            'nilai_diskon' => 15000,
        ]);

        $diskon = $promosi->hitungDiskon(100000);

        $this->assertEquals(15000, $diskon);
    }

    public function test_hitung_diskon_method_does_not_exceed_purchase_amount()
    {
        $promosi = Promosi::factory()->create([
            'tipe_diskon' => 'NOMINAL',
            'nilai_diskon' => 150000,
        ]);

        $diskon = $promosi->hitungDiskon(100000); // Should not exceed purchase amount

        $this->assertEquals(100000, $diskon);
    }

    public function test_hitung_diskon_method_returns_zero_for_invalid_discount_type()
    {
        $promosi = new Promosi([
            'tipe_diskon' => 'INVALID_TYPE', // Invalid type
            'nilai_diskon' => 10,
        ]);

        $diskon = $promosi->hitungDiskon(100000);

        $this->assertEquals(0, $diskon);
    }
}