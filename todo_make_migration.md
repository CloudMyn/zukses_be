# TODO: Missing Migration Files

## Analysis Results

Based on comparison between models in `app/Models/` and existing migration files in `database/migrations/`, the following migration files are missing:

## Complete List of Missing Migrations (31 files)

### 1. Cart & Cart Items
- `create_tb_keranjang_belanja_table.php` - untuk model `Cart`
- `create_tb_item_keranjang_table.php` - untuk model `CartItem`

### 2. Orders & Order Management
- `create_tb_pesanan_table.php` - untuk model `Order`
- `create_tb_item_pesanan_table.php` - untuk model `OrderItem`
- `create_tb_riwayat_status_pesanan_table.php` - untuk model `OrderStatusHistory`
- `create_tb_pengiriman_pesanan_table.php` - untuk model `OrderShipping`

### 3. Payment System
- `create_tb_metode_pembayaran_table.php` - untuk model `PaymentMethod`
- `create_tb_transaksi_pembayaran_table.php` - untuk model `PaymentTransaction`
- `create_tb_log_pembayaran_table.php` - untuk model `PaymentLog`

### 4. Product Reviews
- `create_tb_ulasan_produk_table.php` - untuk model `ProductReview`
- `create_tb_media_ulasan_table.php` - untuk model `ReviewMedia`
- `create_tb_suara_ulasan_table.php` - untuk model `ReviewVote`

### 5. Shipping System
- `create_tb_metode_pengiriman_table.php` - untuk model `ShippingMethod`
- `create_tb_metode_pengiriman_penjual_table.php` - untuk model `SellerShippingMethod`
- `create_tb_tarif_pengiriman_table.php` - untuk model `ShippingRate`

### 6. User Management & Analytics
- `create_tb_pengguna_admin_table.php` - untuk model `AdminUser`
- `create_tb_notifikasi_pengguna_table.php` - untuk model `UserNotification`
- `create_tb_aktivitas_pengguna_table.php` - untuk model `UserActivity`
- `create_tb_riwayat_pencarian_table.php` - untuk model `SearchHistory`

### 7. Reports & Settings
- `create_tb_laporan_penjual_table.php` - untuk model `SellerReport`
- `create_tb_laporan_penjualan_table.php` - untuk model `SalesReport`
- `create_tb_pengaturan_sistem_table.php` - untuk model `SystemSetting`

### 8. Chat System (10 tables)
- `create_tb_chat_percakapan_table.php` - untuk model `ChatConversation`
- `create_tb_chat_peserta_percakapan_table.php` - untuk model `ChatParticipant`
- `create_tb_chat_pesan_chat_table.php` - untuk model `ChatMessage`
- `create_tb_chat_lampiran_pesan_table.php` - untuk model `MessageAttachment`
- `create_tb_chat_status_pesan_table.php` - untuk model `MessageStatus`
- `create_tb_chat_reaksi_pesan_table.php` - untuk model `MessageReaction`
- `create_tb_chat_edit_pesan_table.php` - untuk model `MessageEdit`
- `create_tb_chat_referensi_produk_chat_table.php` - untuk model `ChatProductReference`
- `create_tb_chat_referensi_order_chat_table.php` - untuk model `ChatOrderReference`
- `create_tb_chat_laporan_percakapan_table.php` - untuk model `ChatReport`

### 9. Session Management
- `create_tb_sesi_pengguna_table.php` - untuk model `Session` & `UserSession`

## Commands to Create All Missing Migrations

```bash
# Cart & Cart Items
php artisan make:migration create_tb_keranjang_belanja_table
php artisan make:migration create_tb_item_keranjang_table

# Orders & Order Management
php artisan make:migration create_tb_pesanan_table
php artisan make:migration create_tb_item_pesanan_table
php artisan make:migration create_tb_riwayat_status_pesanan_table
php artisan make:migration create_tb_pengiriman_pesanan_table

# Payment System
php artisan make:migration create_tb_metode_pembayaran_table
php artisan make:migration create_tb_transaksi_pembayaran_table
php artisan make:migration create_tb_log_pembayaran_table

# Product Reviews
php artisan make:migration create_tb_ulasan_produk_table
php artisan make:migration create_tb_media_ulasan_table
php artisan make:migration create_tb_suara_ulasan_table

# Shipping System
php artisan make:migration create_tb_metode_pengiriman_table
php artisan make:migration create_tb_metode_pengiriman_penjual_table
php artisan make:migration create_tb_tarif_pengiriman_table

# User Management & Analytics
php artisan make:migration create_tb_pengguna_admin_table
php artisan make:migration create_tb_notifikasi_pengguna_table
php artisan make:migration create_tb_aktivitas_pengguna_table
php artisan make:migration create_tb_riwayat_pencarian_table

# Reports & Settings
php artisan make:migration create_tb_laporan_penjual_table
php artisan make:migration create_tb_laporan_penjualan_table
php artisan make:migration create_tb_pengaturan_sistem_table

# Chat System (10 tables)
php artisan make:migration create_tb_chat_percakapan_table
php artisan make:migration create_tb_chat_peserta_percakapan_table
php artisan make:migration create_tb_chat_pesan_chat_table
php artisan make:migration create_tb_chat_lampiran_pesan_table
php artisan make:migration create_tb_chat_status_pesan_table
php artisan make:migration create_tb_chat_reaksi_pesan_table
php artisan make:migration create_tb_chat_edit_pesan_table
php artisan make:migration create_tb_chat_referensi_produk_chat_table
php artisan make:migration create_tb_chat_referensi_order_chat_table
php artisan make:migration create_tb_chat_laporan_percakapan_table

# Session Management
php artisan make:migration create_tb_sesi_pengguna_table
```

## Existing Migrations ✅
- `users` table (Model: `User`)
- `tb_penjual` table (Model: `Seller`)
- `tb_alamat` table (Model: `Address`)
- `tb_verifikasi_pengguna` table (Model: `Verification`)
- `tb_perangkat_pengguna` table (Model: `Device`)
- `tb_kategori_produk` table (Model: `CategoryProduct`)
- `tb_produk` table (Model: `Product`)
- `tb_varian_produk` table (Model: `ProductVariant`)
- `tb_nilai_varian_produk` table (Model: `ProductVariantValue`)
- `tb_harga_varian_produk` table (Model: `ProductVariantPrice`)
- `tb_komposisi_harga_varian` table (Model: `VariantPriceComposition`)
- `tb_pengiriman_varian_produk` table (Model: `VariantShippingInfo`)
- `tb_gambar_produk` table (Model: `ProductImage`)
- `tb_info_pengiriman_produk` table (Model: `ProductShippingInfo`)
- `tb_log_inventori` table (Model: `InventoryLog`)
- `master_provinsi` table (Model: `Province`)
- `master_kota` table (Model: `City`)
- `master_kecamatan` table (Model: `District`)
- `master_kode_pos` table (Model: `PostalCode`)

## Instructions for Implementation

### 1. Rename Existing Migration Files (Without tb_ Prefix)
The following existing migration files need to be renamed to follow the `tb_` prefix convention:

```bash
# Rename these files in database/migrations/
mv varian_produk.php tb_varian_produk.php
mv nilai_varian_produk.php tb_nilai_varian_produk.php
mv harga_varian_produk.php tb_harga_varian_produk.php
mv komposisi_harga_varian.php tb_komposisi_harga_varian.php
mv pengiriman_varian_produk.php tb_pengiriman_varian_produk.php
mv gambar_produk.php tb_gambar_produk.php
mv info_pengiriman_produk.php tb_info_pengiriman_produk.php
mv log_inventori.php tb_log_inventori.php
```

### 2. Update Model Table Declarations
After creating all migrations, ensure each model has the correct table name declaration:

```php
// Add this property to each Model class
protected $table = 'tb_nama_tabel';
```

Examples:
```php
// app/Models/Cart.php
protected $table = 'tb_keranjang_belanja';

// app/Models/CartItem.php
protected $table = 'tb_item_keranjang';

// app/Models/Order.php
protected $table = 'tb_pesanan';

// app/Models/OrderItem.php
protected $table = 'tb_item_pesanan';

// ... and so on for all 31 new tables
```

### 3. Execution Order (Critical for Foreign Keys)
Execute migrations in this order to avoid foreign key constraint errors:

#### Phase 1: Foundation Tables
1. `tb_pengguna_admin` (depends on users)
2. `tb_metode_pembayaran`
3. `tb_metode_pengiriman`
4. `tb_pengaturan_sistem`

#### Phase 2: Core E-commerce (in order)
5. `tb_kategori_produk` (already exists)
6. `tb_produk` (already exists)
7. `tb_varian_produk` (already exists)
8. `tb_nilai_varian_produk` (already exists)
9. `tb_harga_varian_produk` (already exists)
10. `tb_komposisi_harga_varian` (already exists)
11. `tb_pengiriman_varian_produk` (already exists)
12. `tb_gambar_produk` (already exists)
13. `tb_info_pengiriman_produk` (already exists)
14. `tb_log_inventori` (already exists)

#### Phase 3: User Interaction Tables
15. `tb_sesi_pengguna`
16. `tb_keranjang_belanja` (depends on users, tb_penjual)
17. `tb_item_keranjang` (depends on tb_keranjang_belanja, tb_produk, tb_harga_varian_produk)
18. `tb_pesanan` (depends on users, tb_alamat)
19. `tb_item_pesanan` (depends on tb_pesanan, tb_penjual, tb_produk, tb_harga_varian_produk)
20. `tb_riwayat_status_pesanan` (depends on tb_pesanan)
21. `tb_pengiriman_pesanan` (depends on tb_pesanan, tb_metode_pengiriman)

#### Phase 4: Payment Tables
22. `tb_transaksi_pembayaran` (depends on tb_pesanan, tb_metode_pembayaran)
23. `tb_log_pembayaran` (depends on tb_transaksi_pembayaran)

#### Phase 5: Shipping Tables
24. `tb_metode_pengiriman_penjual` (depends on tb_penjual, tb_metode_pengiriman)
25. `tb_tarif_pengiriman` (depends on tb_metode_pengiriman, master_provinsi, master_kota)

#### Phase 6: Review & Analytics
26. `tb_ulasan_produk` (depends on tb_produk, tb_harga_varian_produk, users, tb_pesanan)
27. `tb_media_ulasan` (depends on tb_ulasan_produk)
28. `tb_suara_ulasan` (depends on tb_ulasan_produk, users)
29. `tb_notifikasi_pengguna` (depends on users)
30. `tb_aktivitas_pengguna` (depends on users, tb_sesi_pengguna)
31. `tb_riwayat_pencarian` (depends on users)

#### Phase 7: Chat System (execute in order)
32. `tb_chat_percakapan` (depends on users, tb_penjual)
33. `tb_chat_peserta_percakapan` (depends on tb_chat_percakapan, users, tb_penjual)
34. `tb_chat_pesan_chat` (depends on tb_chat_percakapan, users, tb_penjual)
35. `tb_chat_lampiran_pesan` (depends on tb_chat_pesan_chat)
36. `tb_chat_status_pesan` (depends on tb_chat_pesan_chat, users)
37. `tb_chat_reaksi_pesan` (depends on tb_chat_pesan_chat, users)
38. `tb_chat_edit_pesan` (depends on tb_chat_pesan_chat, users)
39. `tb_chat_referensi_produk_chat` (depends on tb_chat_pesan_chat, tb_produk)
40. `tb_chat_referensi_order_chat` (depends on tb_chat_pesan_chat, tb_pesanan)
41. `tb_chat_laporan_percakapan` (depends on tb_chat_percakapan, users)

#### Phase 8: Reports
42. `tb_laporan_penjual` (depends on tb_penjual)
43. `tb_laporan_penjualan` (independent)

### 4. Progress Tracking Template
Use this checklist to track progress as you work:

```markdown
## Migration Progress Checklist

### Phase 1: Foundation
- [ ] `create_tb_pengguna_admin_table.php`
- [ ] `create_tb_metode_pembayaran_table.php`
- [ ] `create_tb_metode_pengiriman_table.php`
- [ ] `create_tb_pengaturan_sistem_table.php`

### Phase 2: Core E-commerce
- [ ] `create_tb_sesi_pengguna_table.php`
- [ ] `create_tb_keranjang_belanja_table.php`
- [ ] `create_tb_item_keranjang_table.php`
- [ ] `create_tb_pesanan_table.php`
- [ ] `create_tb_item_pesanan_table.php`
- [ ] `create_tb_riwayat_status_pesanan_table.php`
- [ ] `create_tb_pengiriman_pesanan_table.php`

### Phase 3: Payment System
- [ ] `create_tb_transaksi_pembayaran_table.php`
- [ ] `create_tb_log_pembayaran_table.php`

### Phase 4: Shipping System
- [ ] `create_tb_metode_pengiriman_penjual_table.php`
- [ ] `create_tb_tarif_pengiriman_table.php`

### Phase 5: Review & Analytics
- [ ] `create_tb_ulasan_produk_table.php`
- [ ] `create_tb_media_ulasan_table.php`
- [ ] `create_tb_suara_ulasan_table.php`
- [ ] `create_tb_notifikasi_pengguna_table.php`
- [ ] `create_tb_aktivitas_pengguna_table.php`
- [ ] `create_tb_riwayat_pencarian_table.php`

### Phase 6: Chat System (10 tables)
- [ ] `create_tb_chat_percakapan_table.php`
- [ ] `create_tb_chat_peserta_percakapan_table.php`
- [ ] `create_tb_chat_pesan_chat_table.php`
- [ ] `create_tb_chat_lampiran_pesan_table.php`
- [ ] `create_tb_chat_status_pesan_table.php`
- [ ] `create_tb_chat_reaksi_pesan_table.php`
- [ ] `create_tb_chat_edit_pesan_table.php`
- [ ] `create_tb_chat_referensi_produk_chat_table.php`
- [ ] `create_tb_chat_referensi_order_chat_table.php`
- [ ] `create_tb_chat_laporan_percakapan_table.php`

### Phase 7: Reports
- [ ] `create_tb_laporan_penjual_table.php`
- [ ] `create_tb_laporan_penjualan_table.php`

### Model Updates
- [ ] Add `$table` property to all 31 new models
- [ ] Verify existing models have correct table names

### File Renames
- [ ] Rename existing migration files without tb_ prefix

**Progress: 0/31 migrations completed**
```

### 5. Final Validation Steps
After completing all migrations:

1. **Run Migration Test:**
   ```bash
   php artisan migrate:fresh
   php artisan db:seed
   ```

2. **Verify Table Creation:**
   ```bash
   mysql -u username -p database_name -e "SHOW TABLES;"
   ```

3. **Check Model Relationships:**
   - Test basic CRUD operations on each model
   - Verify foreign key relationships work correctly

4. **Run Tests:**
   ```bash
   ./vendor/bin/phpunit
   ```

## Total Missing Migrations: 31 files

All migration files use prefix `tb_` for consistency with existing naming convention.