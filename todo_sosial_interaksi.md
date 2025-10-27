# TODO List: Fitur Sosial & Interaksi
**Tanggal Pembuatan:** 27 Oktober 2025  
**Status:** Belum Dimulai  
**Prioritas:** Tinggi  
**Estimasi Waktu:** 3-4 minggu

## Daftar Fitur yang Akan Diimplementasi
1. Product Reviews – Sistem rating & ulasan
2. Q&A Section – Tanya jawab produk
3. Wishlist – Daftar produk favorit
4. Product Sharing – Integrasi berbagi ke media sosial

---

## 1. Product Reviews – Sistem Rating & Ulasan

### 1.1 Migrasi Database
- [ ] Buat file migrasi untuk tabel `tb_ulasan_produk`
  - `id` (primary key)
  - `id_produk` (foreign key ke tb_produk)
  - `id_pengguna` (foreign key ke users)
  - `rating` (integer, 1-5)
  - `judul_ulasan` (string, nullable)
  - `isi_ulasan` (text, nullable)
  - `gambar` (JSON, untuk multiple images, nullable)
  - `status_verifikasi` (boolean, default: false)
  - `tanggal_dibuat` (timestamp)
  - `tanggal_diperbarui` (timestamp)
  - `dihapus_pada` (timestamp, nullable)
- [ ] Tambahkan kolom `jumlah_ulasan` (integer) dan `rating_rata_rata` (decimal) ke tabel `tb_produk`
- [ ] Jalankan migrasi

### 1.2 Model
- [ ] Buat model `UlasanProduk` di `app/Models/UlasanProduk.php`
  - Gunakan soft deletes dengan `use SoftDeletes`
  - Tentukan `$table = 'tb_ulasan_produk'`
  - Tentukan `$fillable` fields
  - Tambahkan `$casts` untuk rating, gambar
  - Buat relasi dengan `Produk` dan `User`
  - Tambahkan accessor untuk rata-rata rating
- [ ] Perbarui model `Produk` dengan:
  - Relasi `ulasan()` (hasMany ke UlasanProduk)
  - Method untuk menghitung dan memperbarui rating rata-rata

### 1.3 Service
- [ ] Buat service `ReviewService` di `app/Services/ReviewService.php`
  - Method `createReview($produkId, $userId, $rating, $judul, $isi, $gambar)`
  - Method `updateReview($reviewId, $userId, $rating, $judul, $isi, $gambar)`
  - Method `deleteReview($reviewId, $userId)`
  - Method `getProductReviews($productId, $page = 1, $perPage = 10)`
  - Method `getProductAverageRating($productId)`
  - Method `getUserReviews($userId, $page = 1, $perPage = 10)`
  - Method `verifyReview($reviewId)` (untuk admin/moderator)

### 1.4 Controller
- [ ] Buat controller `ReviewController` di `app/Http/Controllers/Api/ReviewController.php`
  - Method `store` (POST /api/produk/{id}/ulasan) - Tambah ulasan
  - Method `update` (PUT/PATCH /api/produk/ulasan/{id}) - Edit ulasan
  - Method `destroy` (DELETE /api/produk/ulasan/{id}) - Hapus ulasan
  - Method `index` (GET /api/produk/{id}/ulasan) - Lihat ulasan produk
  - Method `getUserReviews` (GET /api/ulasan/user) - Lihat ulasan pengguna
  - Method `getProductRating` (GET /api/produk/{id}/rating) - Lihat rating produk

### 1.5 API Routes
- [ ] Tambahkan rute API di `routes/api.php`:
  - `POST /api/produk/{id}/ulasan` (tambah ulasan)
  - `PUT/PATCH /api/produk/ulasan/{id}` (edit ulasan)
  - `DELETE /api/produk/ulasan/{id}` (hapus ulasan)
  - `GET /api/produk/{id}/ulasan` (daftar ulasan)
  - `GET /api/ulasan/user` (daftar ulasan pengguna)
  - `GET /api/produk/{id}/rating` (rating produk)

### 1.6 Middleware & Authorization
- [ ] Tambahkan middleware untuk memastikan hanya pengguna yang sudah login bisa memberikan ulasan
- [ ] Implementasikan policy untuk memastikan hanya pengguna yang sama yang bisa mengedit/hapus ulasannya sendiri

### 1.7 Validasi & Request
- [ ] Buat request form untuk validasi input ulasan di `app/Http/Requests/StoreReviewRequest.php`
  - Validasi rating (1-5)
  - Validasi isi (max 1000 karakter)
  - Validasi jumlah gambar (max 5)
  - Validasi ukuran gambar (max 2MB per file)

### 1.8 Testing
- [ ] Buat test unit untuk model UlasanProduk
- [ ] Buat test feature untuk endpoint ulasan
- [ ] Test case: Pengguna bisa menambah ulasan
- [ ] Test case: Pengguna bisa mengedit ulasan sendiri
- [ ] Test case: Pengguna tidak bisa mengedit ulasan orang lain
- [ ] Test case: Rating rata-rata diperbarui setelah ulasan ditambah/ubah

---

## 2. Q&A Section – Tanya Jawab Produk

### 2.1 Migrasi Database
- [ ] Buat file migrasi untuk tabel `tb_pertanyaan_produk`
  - `id` (primary key)
  - `id_produk` (foreign key ke tb_produk)
  - `id_pengguna_penanya` (foreign key ke users)
  - `id_pengguna_pembalas` (foreign key ke users, nullable)
  - `pertanyaan` (text)
  - `jawaban` (text, nullable)
  - `tanggal_pertanyaan` (timestamp)
  - `tanggal_jawaban` (timestamp, nullable)
  - `status_verifikasi` (boolean, default: false)
  - `dihapus_pada` (timestamp, nullable)
- [ ] Jalankan migrasi

### 2.2 Model
- [ ] Buat model `PertanyaanProduk` di `app/Models/PertanyaanProduk.php`
  - Gunakan soft deletes dengan `use SoftDeletes`
  - Tentukan `$table = 'tb_pertanyaan_produk'`
  - Tentukan `$fillable` fields
  - Buat relasi dengan `Produk`, `Penanya` (User), `Pembalas` (User)
- [ ] Tambahkan accessor untuk status terjawab

### 2.3 Service
- [ ] Buat service `QnaService` di `app/Services/QnaService.php`
  - Method `createQuestion($produkId, $userId, $pertanyaan)`
  - Method `answerQuestion($questionId, $userId, $jawaban)`
  - Method `getProductQuestions($productId, $page = 1, $perPage = 10)`
  - Method `getUserQuestions($userId, $page = 1, $perPage = 10)`
  - Method `verifyQuestion($questionId)` (untuk admin/moderator)
  - Method `markAsHelpful($questionId, $userId)` (opsional)

### 2.4 Controller
- [ ] Buat controller `QnaController` di `app/Http/Controllers/Api/QnaController.php`
  - Method `store` (POST /api/produk/{id}/pertanyaan) - Tambah pertanyaan
  - Method `answer` (POST /api/produk/pertanyaan/{id}/jawab) - Jawab pertanyaan
  - Method `index` (GET /api/produk/{id}/pertanyaan) - Lihat pertanyaan produk
  - Method `getUserQuestions` (GET /api/pertanyaan/user) - Lihat pertanyaan pengguna

### 2.5 API Routes
- [ ] Tambahkan rute API di `routes/api.php`:
  - `POST /api/produk/{id}/pertanyaan` (tambah pertanyaan)
  - `POST /api/produk/pertanyaan/{id}/jawab` (jawab pertanyaan)
  - `GET /api/produk/{id}/pertanyaan` (daftar pertanyaan)

### 2.6 Validasi & Request
- [ ] Buat request form untuk validasi input pertanyaan di `app/Http/Requests/StoreQuestionRequest.php`
  - Validasi panjang pertanyaan (min 10, max 500 karakter)
  - Validasi panjang jawaban (max 1000 karakter)

### 2.7 Testing
- [ ] Buat test unit untuk model PertanyaanProduk
- [ ] Buat test feature untuk endpoint pertanyaan
- [ ] Test case: Pengguna bisa menanyakan produk
- [ ] Test case: Admin bisa menjawab pertanyaan
- [ ] Test case: Pengguna tidak bisa menjawab pertanyaan jika bukan admin

---

## 3. Wishlist – Daftar Produk Favorit

### 3.1 Migrasi Database
- [ ] Buat file migrasi untuk tabel `tb_wishlist`
  - `id` (primary key)
  - `id_pengguna` (foreign key ke users)
  - `id_produk` (foreign key ke tb_produk)
  - `dibuat_pada` (timestamp)
  - `diperbarui_pada` (timestamp)
  - `dihapus_pada` (timestamp, nullable)
  - Pastikan kombinasi `id_pengguna` dan `id_produk` unik

### 3.2 Model
- [ ] Buat model `Wishlist` di `app/Models/Wishlist.php`
  - Gunakan soft deletes dengan `use SoftDeletes`
  - Tentukan `$table = 'tb_wishlist'`
  - Tentukan `$fillable` fields
  - Buat relasi dengan `User` dan `Produk`

### 3.3 Service
- [ ] Buat service `WishlistService` di `app/Services/WishlistService.php`
  - Method `addToWishlist($userId, $productId)`
  - Method `removeFromWishlist($userId, $productId)`
  - Method `getUserWishlist($userId, $page = 1, $perPage = 10)`
  - Method `isProductInWishlist($userId, $productId)`
  - Method `getWishlistCount($userId)`

### 3.4 Controller
- [ ] Buat controller `WishlistController` di `app/Http/Controllers/Api/WishlistController.php`
  - Method `store` (POST /api/wishlist) - Tambah ke wishlist
  - Method `destroy` (DELETE /api/wishlist/{produk_id}) - Hapus dari wishlist
  - Method `index` (GET /api/wishlist) - Lihat wishlist pengguna
  - Method `check` (GET /api/wishlist/check/{produk_id}) - Cek apakah produk di wishlist

### 3.5 API Routes
- [ ] Tambahkan rute API di `routes/api.php`:
  - `POST /api/wishlist` (tambah ke wishlist)
  - `DELETE /api/wishlist/{produk_id}` (hapus dari wishlist)
  - `GET /api/wishlist` (daftar wishlist)
  - `GET /api/wishlist/check/{produk_id}` (cek status wishlist)

### 3.6 Testing
- [ ] Buat test unit untuk model Wishlist
- [ ] Buat test feature untuk endpoint wishlist
- [ ] Test case: Pengguna bisa menambah produk ke wishlist
- [ ] Test case: Pengguna bisa menghapus produk dari wishlist
- [ ] Test case: Pengguna bisa melihat daftar wishlist

---

## 4. Product Sharing – Integrasi Berbagi ke Media Sosial

### 4.1 Service
- [ ] Buat service `SocialShareService` di `app/Services/SocialShareService.php`
  - Method `generateShareUrl($productId, $platform)` - Generate URL untuk platform tertentu (facebook, twitter, whatsapp, etc)
  - Method `generateShareMessage($product)` - Generate pesan default untuk berbagi
  - Method `getSharePlatforms()` - Return daftar platform yang didukung

### 4.2 Controller
- [ ] Tambahkan method ke `ProdukController` atau buat `ShareController`
  - Method `getShareUrl` (GET /api/produk/{id}/share-url) - Generate URL berbagi
  - Method `trackShareEvent` (POST /api/produk/{id}/share-track) - Track berbagi (opsional)

### 4.3 API Routes
- [ ] Tambahkan rute API di `routes/api.php`:
  - `GET /api/produk/{id}/share-url` (dapatkan URL berbagi)

### 4.4 Testing
- [ ] Buat test untuk social sharing service
- [ ] Test case: URL berbagi dibuat dengan benar untuk berbagai platform
- [ ] Test case: Pesan berbagi dibuat dengan benar

---

## 5. Integrasi dengan Sistem yang Ada

### 5.1 Update Produk Resource
- [ ] Tambahkan field `jumlah_ulasan`, `rating_rata_rata`, `jumlah_pertanyaan`, `jumlah_di_wishlist` ke ProdukResource

### 5.2 Update Produk Controller
- [ ] Tambahkan method untuk mendapatkan statistik produk (rating, jumlah ulasan, pertanyaan, wishlist)

### 5.3 Update Produk Model
- [ ] Tambahkan relationship dan accessor yang sesuai

### 5.4 Update Produk Routes
- [ ] Pastikan semua endpoint sosial terintegrasi dengan rute produk

---

## 6. UI/UX & Validasi Tambahan

### 6.1 Validasi
- [ ] Pastikan hanya pengguna yang sudah login bisa memberikan ulasan/pertanyaan
- [ ] Validasi bahwa pengguna tidak bisa mengulas/menanyakan produk yang sama lebih dari sekali (opsional)
- [ ] Validasi bahwa hanya admin/seller bisa menjawab pertanyaan

### 6.2 Notifikasi (Opsional)
- [ ] Kirim notifikasi email saat ulasan dijawab
- [ ] Kirim notifikasi saat pertanyaan dijawab

---

## 7. Penanganan Error & Logging

### 7.1 Exception Handling
- [ ] Buat custom exception untuk fitur sosial
- [ ] Tambahkan logging untuk error yang terjadi di fitur sosial

### 7.2 Rate Limiting
- [ ] Implementasi rate limiting untuk mencegah spam ulasan/pertanyaan
- [ ] Tambahkan throttling untuk endpoint social features

---

## 8. Dokumentasi

### 8.1 API Documentation
- [ ] Dokumentasikan semua endpoint baru di API documentation

### 8.2 Kode Comments
- [ ] Tambahkan PHPDoc untuk semua method dan class baru

---

## 9. Deployment Checklist
- [ ] Migration dijalankan di production
- [ ] Environment variables diatur untuk social sharing (jika diperlukan)
- [ ] Backup database sebelum deployment
- [ ] Testing di staging environment

---

## Notes Tambahan
- Semua fitur harus sesuai dengan standar Laravel dan mengikuti struktur yang sudah ada
- Gunakan soft deletes untuk semua tabel yang berisi user-generated content
- Pastikan semua fitur aman dari SQL injection dan XSS
- Gunakan Eloquent ORM untuk query database
- Gunakan Laravel's built-in caching jika diperlukan untuk kinerja