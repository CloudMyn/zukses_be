# Planning Detail Implementasi Sistem Promosi

## 1. Tujuan dan Cakupan Sistem Promosi

Sistem promosi bertujuan untuk mengelola berbagai jenis promosi yang dapat diterapkan pada produk atau pesanan pengguna di platform e-commerce Zukses. Sistem ini akan mendukung berbagai jenis promosi seperti diskon nominal, persentase, pembelian gratis, dan promosi beli-bulan tertentu.

## 2. Fitur-fitur Wajib Sistem Promosi

### 2.1 Manajemen Promosi
- **Fitur Pembuatan Promosi**: Admin dapat membuat promosi baru dengan berbagai parameter
- **Fitur Pengeditan Promosi**: Admin dapat memperbarui detail promosi yang sudah ada
- **Fitur Penghapusan Promosi**: Admin dapat menghapus promosi yang tidak berlaku
- **Fitur Aktivasi/Non-aktivasi Promosi**: Admin dapat mengaktifkan atau menonaktifkan promosi
- **Fitur Pencarian dan Filter Promosi**: Mencari promosi berdasarkan nama, jenis, status, atau periode

### 2.2 Sistem Validasi Promosi
- **Fitur Validasi Kode Promosi**: Validasi apakah kode promosi valid dan dapat digunakan
- **Fitur Penyesuaian dengan Produk**: Cek apakah promosi dapat diterapkan pada produk tertentu
- **Fitur Pembatasan Penggunaan**: Validasi jumlah maksimum penggunaan promosi
- **Fitur Penjadwalan Promosi**: Aktifasi promosi berdasarkan waktu tertentu

### 2.3 Integrasi dengan Sistem Lain
- **Integrasi dengan Keranjang Belanja**: Penerapan promosi pada proses checkout
- **Integrasi dengan Sistem Order**: Penerapan promosi pada pesanan pengguna
- **Integrasi dengan Produk**: Kaitan promosi dengan produk tertentu atau kategori
- **Integrasi dengan Manajemen Pengguna**: Penggunaan promosi berdasarkan pengguna

## 3. Struktur Data dan Field

### 3.1 Model Promosi Utama (tb_promosi)
**Field yang Wajib:**
- `id`: Primary key, auto-increment
- `kode_promosi`: VARCHAR(50), unik, kode yang digunakan pengguna
- `nama_promosi`: VARCHAR(255), nama dari promosi
- `deskripsi`: TEXT, deskripsi detail tentang promosi
- `jenis_promosi`: ENUM('KODE_PROMOSI', 'OTOMATIS', 'MEMBER', 'KELOMPOK_PRODUK'), tipe promosi
- `tipe_diskon`: ENUM('PERSEN', 'NOMINAL', 'BONUS_PRODUK'), tipe dari diskon
- `nilai_diskon`: DECIMAL(10,2), nilai diskon (dalam persen atau nominal)
- `jumlah_maksimum_penggunaan`: INTEGER, jumlah maksimum penggunaan promosi
- `jumlah_penggunaan_saat_ini`: INTEGER, jumlah yang sudah digunakan
- `jumlah_maksimum_penggunaan_per_pengguna`: INTEGER, batas per pengguna
- `tanggal_mulai`: TIMESTAMP, tanggal mulai promosi berlaku
- `tanggal_berakhir`: TIMESTAMP, tanggal berakhir promosi
- `minimum_pembelian`: DECIMAL(10,2), jumlah minimum pembelian untuk promosi
- `id_kategori_produk`: BIGINT UNSIGNED, foreign key ke kategori produk (nullable)
- `dapat_digabungkan`: BOOLEAN, apakah promosi dapat digabungkan dengan promosi lain
- `status_aktif`: BOOLEAN, status apakah promosi sedang aktif
- `id_pembuat`: BIGINT UNSIGNED, foreign key ke user yang membuat
- `id_pembaharuan_terakhir`: BIGINT UNSIGNED, foreign key ke user yang memperbarui
- `dibuat_pada`: TIMESTAMP, tanggal dibuat
- `diperbarui_pada`: TIMESTAMP, tanggal diperbarui

### 3.2 Model Produk Promosi (tb_produk_promosi)
**Field yang Wajib:**
- `id`: Primary key, auto-increment
- `id_promosi`: BIGINT UNSIGNED, foreign key ke promosi
- `id_produk`: BIGINT UNSIGNED, foreign key ke produk
- `dibuat_pada`: TIMESTAMP, tanggal dibuat

### 3.3 Model Riwayat Penggunaan Promosi (tb_riwayat_penggunaan_promosi)
**Field yang Wajib:**
- `id`: Primary key, auto-increment
- `id_promosi`: BIGINT UNSIGNED, foreign key ke promosi
- `id_pengguna`: BIGINT UNSIGNED, foreign key ke user
- `id_pesanan`: BIGINT UNSIGNED, foreign key ke order (nullable)
- `tanggal_penggunaan`: TIMESTAMP, tanggal promosi digunakan
- `jumlah_diskon_diterapkan`: DECIMAL(10,2), jumlah diskon yang diterapkan
- `dibuat_pada`: TIMESTAMP, tanggal dibuat

## 4. Implementasi Model dan Method Relasi

### 4.1 Model Promosi (App\Models\Promosi)
```php
class Promosi extends Model
{
    protected $table = 'tb_promosi';
    
    // Relasi ke User pembuat
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'id_pembuat', 'id');
    }
    
    // Relasi ke User pembaharu terakhir
    public function pembaharu()
    {
        return $this->belongsTo(User::class, 'id_pembaharu_terakhir', 'id');
    }
    
    // Relasi ke Kategori Produk
    public function kategori_produk()
    {
        return $this->belongsTo(KategoriProduk::class, 'id_kategori_produk', 'id');
    }
    
    // Relasi ke Produk (many-to-many melalui pivot table)
    public function produk()
    {
        return $this->belongsToMany(Produk::class, 'tb_produk_promosi', 'id_promosi', 'id_produk');
    }
    
    // Relasi ke Riwayat Penggunaan Promosi
    public function riwayat_penggunaan()
    {
        return $this->hasMany(RiwayatPenggunaanPromosi::class, 'id_promosi', 'id');
    }
    
    // Method untuk mengecek apakah promosi aktif
    public function isActive()
    {
        return $this->status_aktif && 
               now()->between($this->tanggal_mulai, $this->tanggal_berakhir) &&
               $this->jumlah_penggunaan_saat_ini < $this->jumlah_maksimum_penggunaan;
    }
    
    // Method untuk menghitung total diskon
    public function hitungDiskon($jumlah_pembelian)
    {
        switch ($this->tipe_diskon) {
            case 'PERSEN':
                return ($jumlah_pembelian * $this->nilai_diskon) / 100;
            case 'NOMINAL':
                return min($this->nilai_diskon, $jumlah_pembelian);
            default:
                return 0;
        }
    }
}
```

### 4.2 Model Produk Promosi (App\Models\ProdukPromosi)
```php
class ProdukPromosi extends Model
{
    protected $table = 'tb_produk_promosi';
    
    // Relasi ke Promosi
    public function promosi()
    {
        return $this->belongsTo(Promosi::class, 'id_promosi', 'id');
    }
    
    // Relasi ke Produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id');
    }
}
```

### 4.3 Model Riwayat Penggunaan Promosi (App\Models\RiwayatPenggunaanPromosi)
```php
class RiwayatPenggunaanPromosi extends Model
{
    protected $table = 'tb_riwayat_penggunaan_promosi';
    
    // Relasi ke Promosi
    public function promosi()
    {
        return $this->belongsTo(Promosi::class, 'id_promosi', 'id');
    }
    
    // Relasi ke Pengguna
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id');
    }
    
    // Relasi ke Pesanan
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id');
    }
}
```

## 5. File Migrasi

### 5.1 Migrasi tb_promosi
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tb_promosi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_promosi', 50)->unique();
            $table->string('nama_promosi', 255);
            $table->text('deskripsi')->nullable();
            $table->enum('jenis_promosi', ['KODE_PROMOSI', 'OTOMATIS', 'MEMBER', 'KELOMPOK_PRODUK']);
            $table->enum('tipe_diskon', ['PERSEN', 'NOMINAL', 'BONUS_PRODUK']);
            $table->decimal('nilai_diskon', 10, 2);
            $table->integer('jumlah_maksimum_penggunaan')->default(0);
            $table->integer('jumlah_penggunaan_saat_ini')->default(0);
            $table->integer('jumlah_maksimum_penggunaan_per_pengguna')->default(0);
            $table->timestamp('tanggal_mulai');
            $table->timestamp('tanggal_berakhir');
            $table->decimal('minimum_pembelian', 10, 2)->default(0);
            $table->unsignedBigInteger('id_kategori_produk')->nullable();
            $table->boolean('dapat_digabungkan')->default(false);
            $table->boolean('status_aktif')->default(false);
            $table->unsignedBigInteger('id_pembuat');
            $table->unsignedBigInteger('id_pembaharu_terakhir')->nullable();
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
            
            // Foreign Keys
            $table->foreign('id_kategori_produk')->references('id')->on('tb_kategori_produk');
            $table->foreign('id_pembuat')->references('id')->on('users');
            $table->foreign('id_pembaharu_terakhir')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tb_promosi');
    }
};
```

### 5.2 Migrasi tb_produk_promosi
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tb_produk_promosi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_promosi');
            $table->unsignedBigInteger('id_produk');
            $table->timestamp('dibuat_pada')->nullable();
            
            // Foreign Keys
            $table->foreign('id_promosi')->references('id')->on('tb_promosi')->onDelete('cascade');
            $table->foreign('id_produk')->references('id')->on('tb_produk')->onDelete('cascade');
            
            // Unique constraint to prevent duplicate promo-product combination
            $table->unique(['id_promosi', 'id_produk']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tb_produk_promosi');
    }
};
```

### 5.3 Migrasi tb_riwayat_penggunaan_promosi
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tb_riwayat_penggunaan_promosi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_promosi');
            $table->unsignedBigInteger('id_pengguna');
            $table->unsignedBigInteger('id_pesanan')->nullable();
            $table->timestamp('tanggal_penggunaan');
            $table->decimal('jumlah_diskon_diterapkan', 10, 2);
            $table->timestamp('dibuat_pada')->nullable();
            
            // Foreign Keys
            $table->foreign('id_promosi')->references('id')->on('tb_promosi');
            $table->foreign('id_pengguna')->references('id')->on('users');
            $table->foreign('id_pesanan')->references('id')->on('tb_pesanan');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tb_riwayat_penggunaan_promosi');
    }
};
```

## 6. Factory dan Seeder

### 6.1 PromosiFactory
```php
<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Promosi;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromosiFactory extends Factory
{
    protected $model = Promosi::class;

    public function definition(): array
    {
        return [
            'kode_promosi' => $this->faker->unique()->bothify('PROMO-#####'),
            'nama_promosi' => $this->faker->sentence(3),
            'deskripsi' => $this->faker->paragraph,
            'jenis_promosi' => $this->faker->randomElement(['KODE_PROMOSI', 'OTOMATIS', 'MEMBER', 'KELOMPOK_PRODUK']),
            'tipe_diskon' => $this->faker->randomElement(['PERSEN', 'NOMINAL', 'BONUS_PRODUK']),
            'nilai_diskon' => $this->faker->randomElement([10, 15, 20, 25, 30, 50000, 100000, 150000]),
            'jumlah_maksimum_penggunaan' => $this->faker->numberBetween(100, 1000),
            'jumlah_penggunaan_saat_ini' => 0,
            'jumlah_maksimum_penggunaan_per_pengguna' => $this->faker->numberBetween(1, 5),
            'tanggal_mulai' => now()->subDays(rand(1, 10)),
            'tanggal_berakhir' => now()->addDays(rand(15, 60)),
            'minimum_pembelian' => $this->faker->randomElement([50000, 100000, 200000, 500000]),
            'id_kategori_produk' => null,
            'dapat_digabungkan' => $this->faker->boolean,
            'status_aktif' => $this->faker->boolean,
            'id_pembuat' => User::factory(),
            'id_pembaharu_terakhir' => User::factory(),
            'dibuat_pada' => now(),
            'diperbarui_pada' => now(),
        ];
    }
}
```

### 6.2 ProdukPromosiFactory
```php
<?php

namespace Database\Factories;

use App\Models\Promosi;
use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProdukPromosiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_promosi' => Promosi::factory(),
            'id_produk' => Produk::factory(),
            'dibuat_pada' => now(),
        ];
    }
}
```

### 6.3 RiwayatPenggunaanPromosiFactory
```php
<?php

namespace Database\Factories;

use App\Models\Promosi;
use App\Models\User;
use App\Models\Pesanan;
use Illuminate\Database\Eloquent\Factories\Factory;

class RiwayatPenggunaanPromosiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_promosi' => Promosi::factory(),
            'id_pengguna' => User::factory(),
            'id_pesanan' => Pesanan::factory(),
            'tanggal_penggunaan' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'jumlah_diskon_diterapkan' => $this->faker->randomElement([10000, 15000, 20000, 25000, 30000]),
            'dibuat_pada' => now(),
        ];
    }
}
```

### 6.4 Seeder Sistem Promosi
```php
<?php

namespace Database\Seeders;

use App\Models\Promosi;
use App\Models\ProdukPromosi;
use App\Models\RiwayatPenggunaanPromosi;
use Illuminate\Database\Seeder;

class PromosiSeeder extends Seeder
{
    public function run()
    {
        // Buat promosi contoh
        $promoList = [
            [
                'kode_promosi' => 'WELCOME10',
                'nama_promosi' => 'Promo Selamat Datang',
                'deskripsi' => 'Diskon 10% untuk pengguna baru',
                'jenis_promosi' => 'KODE_PROMOSI',
                'tipe_diskon' => 'PERSEN',
                'nilai_diskon' => 10,
                'jumlah_maksimum_penggunaan' => 1000,
                'jumlah_maksimum_penggunaan_per_pengguna' => 1,
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addDays(30),
                'minimum_pembelian' => 50000,
                'dapat_digabungkan' => false,
                'status_aktif' => true,
            ],
            [
                'kode_promosi' => 'BULANAN20',
                'nama_promosi' => 'Promo Bulanan',
                'deskripsi' => 'Diskon 20% untuk produk elektronik',
                'jenis_promosi' => 'KELOMPOK_PRODUK',
                'tipe_diskon' => 'PERSEN',
                'nilai_diskon' => 20,
                'jumlah_maksimum_penggunaan' => 500,
                'jumlah_maksimum_penggunaan_per_pengguna' => 2,
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addDays(14),
                'minimum_pembelian' => 100000,
                'dapat_digabungkan' => true,
                'status_aktif' => true,
            ],
            [
                'kode_promosi' => 'FREESHIP50',
                'nama_promosi' => 'Gratis Ongkir',
                'deskripsi' => 'Gratis ongkir untuk pembelian di atas Rp50.000',
                'jenis_promosi' => 'AUTOMATIS',
                'tipe_diskon' => 'NOMINAL',
                'nilai_diskon' => 20000,
                'jumlah_maksimum_penggunaan' => 2000,
                'jumlah_maksimum_penggunaan_per_pengguna' => 10,
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addDays(7),
                'minimum_pembelian' => 50000,
                'dapat_digabungkan' => true,
                'status_aktif' => true,
            ]
        ];

        foreach ($promoList as $promoData) {
            $promosi = Promosi::create($promoData);
        }

        // Buat beberapa relasi produk-promo
        ProdukPromosi::factory(20)->create();

        // Buat beberapa riwayat penggunaan
        RiwayatPenggunaanPromosi::factory(50)->create();
    }
}
```

## 7. TODO List Implementasi

### 7.1 Persiapan Awal
- [ ] Buat file migrasi untuk tabel tb_promosi
- [ ] Buat file migrasi untuk tabel tb_produk_promosi
- [ ] Buat file migrasi untuk tabel tb_riwayat_penggunaan_promosi
- [ ] Jalankan migrasi database
- [ ] Validasi struktur tabel setelah migrasi

### 7.2 Implementasi Model dan Relasi
- [ ] Buat model Promosi dengan semua relasi dan method
- [ ] Buat model ProdukPromosi dengan relasi yang sesuai
- [ ] Buat model RiwayatPenggunaanPromosi dengan relasi yang sesuai
- [ ] Uji coba relasi antar model
- [ ] Tambahkan accessor dan mutator jika diperlukan
- [ ] Implementasikan soft deletes jika diperlukan

### 7.3 Implementasi Factory dan Seeder
- [ ] Buat PromosiFactory dengan data acak dan realistis
- [ ] Buat ProdukPromosiFactory
- [ ] Buat RiwayatPenggunaanPromosiFactory
- [ ] Buat PromosiSeeder dengan data contoh
- [ ] Jalankan seeder dan verifikasi data

### 7.4 Implementasi Controller dan API
- [ ] Buat PromosiController dengan CRUD operations
- [ ] Implementasi endpoint untuk membuat promosi
- [ ] Implementasi endpoint untuk membaca daftar promosi
- [ ] Implementasi endpoint untuk membaca detail promosi
- [ ] Implementasi endpoint untuk memperbarui promosi
- [ ] Implementasi endpoint untuk menghapus promosi
- [ ] Implementasi endpoint untuk validasi kode promosi

### 7.5 Implementasi Validasi dan Business Logic
- [ ] Buat request validation untuk promosi
- [ ] Implementasi logic validasi promosi aktif
- [ ] Implementasi logic validasi batas penggunaan
- [ ] Implementasi logic validasi minimum pembelian
- [ ] Implementasi logic validasi produk yang terkait
- [ ] Implementasi logic penghitungan diskon

### 7.6 Implementasi Resource dan Response Format
- [ ] Buat PromosiResource untuk format API response
- [ ] Buat PromosiCollection untuk format response list
- [ ] Implementasi format response sesuai standar API
- [ ] Tambahkan error handling dan pesan yang sesuai

### 7.7 Implementasi Otentikasi dan Otorisasi
- [ ] Tambahkan middleware JWT untuk endpoint promosi
- [ ] Implementasi role-based access control
- [ ] Pastikan hanya admin yang dapat mengelola promosi
- [ ] Implementasi permission checking

### 7.8 Implementasi Fitur Validasi di Checkout
- [ ] Integrasi promosi dengan sistem keranjang
- [ ] Implementasi validasi promosi saat checkout
- [ ] Implementasi aplikasi diskon ke pesanan
- [ ] Simpan riwayat penggunaan promosi

### 7.9 Implementasi Fitur Laporan
- [ ] Endpoint untuk melihat laporan penggunaan promosi
- [ ] Endpoint untuk melihat promosi paling populer
- [ ] Implementasi filter dan sorting laporan
- [ ] Format laporan dalam format JSON dan PDF

### 7.10 Testing
- [ ] Buat unit test untuk model promosi
- [ ] Buat feature test untuk endpoint promosi
- [ ] Test validasi promosi saat checkout
- [ ] Test boundary conditions (batas penggunaan, tanggal, dll)
- [ ] Test integrasi dengan sistem pesanan
- [ ] Test error handling scenarios

### 7.11 Dokumentasi API
- [ ] Buat dokumentasi API endpoint promosi
- [ ] Tambahkan contoh request dan response
- [ ] Dokumentasikan parameter validasi
- [ ] Tambahkan dokumentasi penggunaan promosi

### 7.12 Optimasi dan Validasi Akhir
- [ ] Optimasi query database jika diperlukan
- [ ] Lakukan load testing untuk endpoint promosi
- [ ] Validasi keamanan input dan SQL injection
- [ ] Validasi sanitasi data keluaran
- [ ] Review dan perbaikan kodingan sesuai standar
- [ ] Deployment dan uji coba di environment staging

## 8. Tambahan Fitur Lanjutan (Opsional)

### 8.1 Fitur Advanced
- [ ] Sistem A/B testing untuk promosi
- [ ] Implementasi promosi berdasarkan perilaku pengguna
- [ ] Implementasi promosi berdasarkan lokasi pengguna
- [ ] Sistem notifikasi promosi untuk pengguna tertentu

### 8.2 Fitur Analitik
- [ ] Dashboard analitik promosi
- [ ] Laporan konversi promosi
- [ ] Prediksi efektifitas promosi
- [ ] Rekomendasi promosi berdasarkan data

### 8.3 Fitur Otomatisasi
- [ ] Scheduler untuk aktivasi promosi otomatis
- [ ] Notifikasi otomatis saat promosi akan berakhir
- [ ] Sistem backup promosi
- [ ] Audit log untuk perubahan promosi