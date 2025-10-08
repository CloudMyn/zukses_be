# DBML to Code Implementation Plan

## Table of Contents
- [User Management Group](#user-management-group)
- [Address Management Group](#address-management-group)
- [Product Management Group](#product-management-group)
- [Cart & Order Management Group](#cart--order-management-group)
- [Shipping Management Group](#shipping-management-group)
- [Payment Management Group](#payment-management-group)
- [Review Management Group](#review-management-group)
- [Notification & Activity Management Group](#notification--activity-management-group)
- [Admin & Reporting Group](#admin--reporting-group)
- [System Settings Group](#system-settings-group)
- [Chat System Group](#chat-system-group)
- [Master Address System Group](#master-address-system-group)

---

## User Management Group

### Tables in this group:
- tb_users
- tb_penjual
- tb_verifikasi_pengguna
- tb_sesi_pengguna
- tb_perangkat_pengguna

### tb_users
- [ ] **Migration:** Create migration file for tb_users table
- [ ] **Model:** Create User model with fillable, cast, and relation configurations
- [ ] **Factory:** Create User factory for testing
- [ ] **Seeder:** Create User seeder
- [ ] **Policy:** Create User policy for authorization
- [ ] **Create Request:** Create UserCreateRequest validation
- [ ] **Update Request:** Create UserUpdateRequest validation

### tb_penjual
- [ ] **Migration:** Create migration file for tb_penjual table
- [ ] **Model:** Create Seller model with fillable, cast, and relation configurations
- [ ] **Factory:** Create Seller factory for testing
- [ ] **Seeder:** Create Seller seeder
- [ ] **Policy:** Create Seller policy for authorization
- [ ] **Create Request:** Create SellerCreateRequest validation
- [ ] **Update Request:** Create SellerUpdateRequest validation

### tb_verifikasi_pengguna
- [ ] **Migration:** Create migration file for tb_verifikasi_pengguna table
- [ ] **Model:** Create Verification model with fillable, cast, and relation configurations
- [ ] **Factory:** Create Verification factory for testing
- [ ] **Seeder:** Create Verification seeder
- [ ] **Policy:** Create Verification policy for authorization
- [ ] **Create Request:** Create VerificationCreateRequest validation
- [ ] **Update Request:** Create VerificationUpdateRequest validation

### tb_sesi_pengguna
- [ ] **Migration:** Create migration file for tb_sesi_pengguna table
- [ ] **Model:** Create Session model with fillable, cast, and relation configurations
- [ ] **Factory:** Create Session factory for testing
- [ ] **Seeder:** Create Session seeder
- [ ] **Policy:** Create Session policy for authorization
- [ ] **Create Request:** Create SessionCreateRequest validation
- [ ] **Update Request:** Create SessionUpdateRequest validation

### tb_perangkat_pengguna
- [ ] **Migration:** Create migration file for tb_perangkat_pengguna table
- [ ] **Model:** Create Device model with fillable, cast, and relation configurations
- [ ] **Factory:** Create Device factory for testing
- [ ] **Seeder:** Create Device seeder
- [ ] **Policy:** Create Device policy for authorization
- [ ] **Create Request:** Create DeviceCreateRequest validation
- [ ] **Update Request:** Create DeviceUpdateRequest validation

---

## Address Management Group

### Tables in this group:
- tb_alamat

### tb_alamat
- [ ] **Migration:** Create migration file for tb_alamat table
- [ ] **Model:** Create Address model with fillable, cast, and relation configurations
- [ ] **Factory:** Create Address factory for testing
- [ ] **Seeder:** Create Address seeder
- [ ] **Policy:** Create Address policy for authorization
- [ ] **Create Request:** Create AddressCreateRequest validation
- [ ] **Update Request:** Create AddressUpdateRequest validation

---

## Product Management Group

### Tables in this group:
- tb_kategori_produk
- tb_produk
- tb_varian_produk
- tb_gambar_produk
- tb_info_pengiriman_produk
- tb_log_inventori

### tb_kategori_produk
- [ ] **Migration:** Create migration file for tb_kategori_produk table
- [ ] **Model:** Create CategoryProduct model with fillable, cast, and relation configurations
- [ ] **Factory:** Create CategoryProduct factory for testing
- [ ] **Seeder:** Create CategoryProduct seeder
- [ ] **Policy:** Create CategoryProduct policy for authorization
- [ ] **Create Request:** Create CategoryProductCreateRequest validation
- [ ] **Update Request:** Create CategoryProductUpdateRequest validation

### tb_produk
- [ ] **Migration:** Create migration file for tb_produk table
- [ ] **Model:** Create Product model with fillable, cast, and relation configurations
- [ ] **Factory:** Create Product factory for testing
- [ ] **Seeder:** Create Product seeder
- [ ] **Policy:** Create Product policy for authorization
- [ ] **Create Request:** Create ProductCreateRequest validation
- [ ] **Update Request:** Create ProductUpdateRequest validation

### tb_varian_produk
- [ ] **Migration:** Create migration file for tb_varian_produk table
- [ ] **Model:** Create ProductVariant model with fillable, cast, and relation configurations
- [ ] **Factory:** Create ProductVariant factory for testing
- [ ] **Seeder:** Create ProductVariant seeder
- [ ] **Policy:** Create ProductVariant policy for authorization
- [ ] **Create Request:** Create ProductVariantCreateRequest validation
- [ ] **Update Request:** Create ProductVariantUpdateRequest validation

### tb_gambar_produk
- [ ] **Migration:** Create migration file for tb_gambar_produk table
- [ ] **Model:** Create ProductImage model with fillable, cast, and relation configurations
- [ ] **Factory:** Create ProductImage factory for testing
- [ ] **Seeder:** Create ProductImage seeder
- [ ] **Policy:** Create ProductImage policy for authorization
- [ ] **Create Request:** Create ProductImageCreateRequest validation
- [ ] **Update Request:** Create ProductImageUpdateRequest validation

### tb_info_pengiriman_produk
- [ ] **Migration:** Create migration file for tb_info_pengiriman_produk table
- [ ] **Model:** Create ProductShippingInfo model with fillable, cast, and relation configurations
- [ ] **Factory:** Create ProductShippingInfo factory for testing
- [ ] **Seeder:** Create ProductShippingInfo seeder
- [ ] **Policy:** Create ProductShippingInfo policy for authorization
- [ ] **Create Request:** Create ProductShippingInfoCreateRequest validation
- [ ] **Update Request:** Create ProductShippingInfoUpdateRequest validation

### tb_log_inventori
- [ ] **Migration:** Create migration file for tb_log_inventori table
- [ ] **Model:** Create InventoryLog model with fillable, cast, and relation configurations
- [ ] **Factory:** Create InventoryLog factory for testing
- [ ] **Seeder:** Create InventoryLog seeder
- [ ] **Policy:** Create InventoryLog policy for authorization
- [ ] **Create Request:** Create InventoryLogCreateRequest validation
- [ ] **Update Request:** Create InventoryLogUpdateRequest validation

---

## Cart & Order Management Group

### Tables in this group:
- tb_keranjang_belanja
- tb_item_keranjang
- tb_pesanan
- tb_item_pesanan
- tb_riwayat_status_pesanan

### tb_keranjang_belanja
- [ ] **Migration:** Create migration file for tb_keranjang_belanja table
- [ ] **Model:** Create Cart model with fillable, cast, and relation configurations
- [ ] **Factory:** Create Cart factory for testing
- [ ] **Seeder:** Create Cart seeder
- [ ] **Policy:** Create Cart policy for authorization
- [ ] **Create Request:** Create CartCreateRequest validation
- [ ] **Update Request:** Create CartUpdateRequest validation

### tb_item_keranjang
- [ ] **Migration:** Create migration file for tb_item_keranjang table
- [ ] **Model:** Create CartItem model with fillable, cast, and relation configurations
- [ ] **Factory:** Create CartItem factory for testing
- [ ] **Seeder:** Create CartItem seeder
- [ ] **Policy:** Create CartItem policy for authorization
- [ ] **Create Request:** Create CartItemCreateRequest validation
- [ ] **Update Request:** Create CartItemUpdateRequest validation

### tb_pesanan
- [ ] **Migration:** Create migration file for tb_pesanan table
- [ ] **Model:** Create Order model with fillable, cast, and relation configurations
- [ ] **Factory:** Create Order factory for testing
- [ ] **Seeder:** Create Order seeder
- [ ] **Policy:** Create Order policy for authorization
- [ ] **Create Request:** Create OrderCreateRequest validation
- [ ] **Update Request:** Create OrderUpdateRequest validation

### tb_item_pesanan
- [ ] **Migration:** Create migration file for tb_item_pesanan table
- [ ] **Model:** Create OrderItem model with fillable, cast, and relation configurations
- [ ] **Factory:** Create OrderItem factory for testing
- [ ] **Seeder:** Create OrderItem seeder
- [ ] **Policy:** Create OrderItem policy for authorization
- [ ] **Create Request:** Create OrderItemCreateRequest validation
- [ ] **Update Request:** Create OrderItemUpdateRequest validation

### tb_riwayat_status_pesanan
- [ ] **Migration:** Create migration file for tb_riwayat_status_pesanan table
- [ ] **Model:** Create OrderStatusHistory model with fillable, cast, and relation configurations
- [ ] **Factory:** Create OrderStatusHistory factory for testing
- [ ] **Seeder:** Create OrderStatusHistory seeder
- [ ] **Policy:** Create OrderStatusHistory policy for authorization
- [ ] **Create Request:** Create OrderStatusHistoryCreateRequest validation
- [ ] **Update Request:** Create OrderStatusHistoryUpdateRequest validation

---

## Shipping Management Group

### Tables in this group:
- tb_metode_pengiriman
- tb_metode_pengiriman_penjual
- tb_tarif_pengiriman
- tb_pengiriman_pesanan

### tb_metode_pengiriman
- [ ] **Migration:** Create migration file for tb_metode_pengiriman table
- [ ] **Model:** Create ShippingMethod model with fillable, cast, and relation configurations
- [ ] **Factory:** Create ShippingMethod factory for testing
- [ ] **Seeder:** Create ShippingMethod seeder
- [ ] **Policy:** Create ShippingMethod policy for authorization
- [ ] **Create Request:** Create ShippingMethodCreateRequest validation
- [ ] **Update Request:** Create ShippingMethodUpdateRequest validation

### tb_metode_pengiriman_penjual
- [ ] **Migration:** Create migration file for tb_metode_pengiriman_penjual table
- [ ] **Model:** Create SellerShippingMethod model with fillable, cast, and relation configurations
- [ ] **Factory:** Create SellerShippingMethod factory for testing
- [ ] **Seeder:** Create SellerShippingMethod seeder
- [ ] **Policy:** Create SellerShippingMethod policy for authorization
- [ ] **Create Request:** Create SellerShippingMethodCreateRequest validation
- [ ] **Update Request:** Create SellerShippingMethodUpdateRequest validation

### tb_tarif_pengiriman
- [ ] **Migration:** Create migration file for tb_tarif_pengiriman table
- [ ] **Model:** Create ShippingRate model with fillable, cast, and relation configurations
- [ ] **Factory:** Create ShippingRate factory for testing
- [ ] **Seeder:** Create ShippingRate seeder
- [ ] **Policy:** Create ShippingRate policy for authorization
- [ ] **Create Request:** Create ShippingRateCreateRequest validation
- [ ] **Update Request:** Create ShippingRateUpdateRequest validation

### tb_pengiriman_pesanan
- [ ] **Migration:** Create migration file for tb_pengiriman_pesanan table
- [ ] **Model:** Create OrderShipping model with fillable, cast, and relation configurations
- [ ] **Factory:** Create OrderShipping factory for testing
- [ ] **Seeder:** Create OrderShipping seeder
- [ ] **Policy:** Create OrderShipping policy for authorization
- [ ] **Create Request:** Create OrderShippingCreateRequest validation
- [ ] **Update Request:** Create OrderShippingUpdateRequest validation

---

## Payment Management Group

### Tables in this group:
- tb_metode_pembayaran
- tb_transaksi_pembayaran
- tb_log_pembayaran

### tb_metode_pembayaran
- [ ] **Migration:** Create migration file for tb_metode_pembayaran table
- [ ] **Model:** Create PaymentMethod model with fillable, cast, and relation configurations
- [ ] **Factory:** Create PaymentMethod factory for testing
- [ ] **Seeder:** Create PaymentMethod seeder
- [ ] **Policy:** Create PaymentMethod policy for authorization
- [ ] **Create Request:** Create PaymentMethodCreateRequest validation
- [ ] **Update Request:** Create PaymentMethodUpdateRequest validation

### tb_transaksi_pembayaran
- [ ] **Migration:** Create migration file for tb_transaksi_pembayaran table
- [ ] **Model:** Create PaymentTransaction model with fillable, cast, and relation configurations
- [ ] **Factory:** Create PaymentTransaction factory for testing
- [ ] **Seeder:** Create PaymentTransaction seeder
- [ ] **Policy:** Create PaymentTransaction policy for authorization
- [ ] **Create Request:** Create PaymentTransactionCreateRequest validation
- [ ] **Update Request:** Create PaymentTransactionUpdateRequest validation

### tb_log_pembayaran
- [ ] **Migration:** Create migration file for tb_log_pembayaran table
- [ ] **Model:** Create PaymentLog model with fillable, cast, and relation configurations
- [ ] **Factory:** Create PaymentLog factory for testing
- [ ] **Seeder:** Create PaymentLog seeder
- [ ] **Policy:** Create PaymentLog policy for authorization
- [ ] **Create Request:** Create PaymentLogCreateRequest validation
- [ ] **Update Request:** Create PaymentLogUpdateRequest validation

---

## Review Management Group

### Tables in this group:
- tb_ulasan_produk
- tb_media_ulasan
- tb_suara_ulasan

### tb_ulasan_produk
- [ ] **Migration:** Create migration file for tb_ulasan_produk table
- [ ] **Model:** Create ProductReview model with fillable, cast, and relation configurations
- [ ] **Factory:** Create ProductReview factory for testing
- [ ] **Seeder:** Create ProductReview seeder
- [ ] **Policy:** Create ProductReview policy for authorization
- [ ] **Create Request:** Create ProductReviewCreateRequest validation
- [ ] **Update Request:** Create ProductReviewUpdateRequest validation

### tb_media_ulasan
- [ ] **Migration:** Create migration file for tb_media_ulasan table
- [ ] **Model:** Create ReviewMedia model with fillable, cast, and relation configurations
- [ ] **Factory:** Create ReviewMedia factory for testing
- [ ] **Seeder:** Create ReviewMedia seeder
- [ ] **Policy:** Create ReviewMedia policy for authorization
- [ ] **Create Request:** Create ReviewMediaCreateRequest validation
- [ ] **Update Request:** Create ReviewMediaUpdateRequest validation

### tb_suara_ulasan
- [ ] **Migration:** Create migration file for tb_suara_ulasan table
- [ ] **Model:** Create ReviewVote model with fillable, cast, and relation configurations
- [ ] **Factory:** Create ReviewVote factory for testing
- [ ] **Seeder:** Create ReviewVote seeder
- [ ] **Policy:** Create ReviewVote policy for authorization
- [ ] **Create Request:** Create ReviewVoteCreateRequest validation
- [ ] **Update Request:** Create ReviewVoteUpdateRequest validation

---

## Notification & Activity Management Group

### Tables in this group:
- tb_notifikasi_pengguna
- tb_aktivitas_pengguna
- tb_riwayat_pencarian

### tb_notifikasi_pengguna
- [ ] **Migration:** Create migration file for tb_notifikasi_pengguna table
- [ ] **Model:** Create UserNotification model with fillable, cast, and relation configurations
- [ ] **Factory:** Create UserNotification factory for testing
- [ ] **Seeder:** Create UserNotification seeder
- [ ] **Policy:** Create UserNotification policy for authorization
- [ ] **Create Request:** Create UserNotificationCreateRequest validation
- [ ] **Update Request:** Create UserNotificationUpdateRequest validation

### tb_aktivitas_pengguna
- [ ] **Migration:** Create migration file for tb_aktivitas_pengguna table
- [ ] **Model:** Create UserActivity model with fillable, cast, and relation configurations
- [ ] **Factory:** Create UserActivity factory for testing
- [ ] **Seeder:** Create UserActivity seeder
- [ ] **Policy:** Create UserActivity policy for authorization
- [ ] **Create Request:** Create UserActivityCreateRequest validation
- [ ] **Update Request:** Create UserActivityUpdateRequest validation

### tb_riwayat_pencarian
- [ ] **Migration:** Create migration file for tb_riwayat_pencarian table
- [ ] **Model:** Create SearchHistory model with fillable, cast, and relation configurations
- [ ] **Factory:** Create SearchHistory factory for testing
- [ ] **Seeder:** Create SearchHistory seeder
- [ ] **Policy:** Create SearchHistory policy for authorization
- [ ] **Create Request:** Create SearchHistoryCreateRequest validation
- [ ] **Update Request:** Create SearchHistoryUpdateRequest validation

---

## Admin & Reporting Group

### Tables in this group:
- tb_pengguna_admin
- tb_laporan_penjual
- tb_laporan_penjualan

### tb_pengguna_admin
- [ ] **Migration:** Create migration file for tb_pengguna_admin table
- [ ] **Model:** Create AdminUser model with fillable, cast, and relation configurations
- [ ] **Factory:** Create AdminUser factory for testing
- [ ] **Seeder:** Create AdminUser seeder
- [ ] **Policy:** Create AdminUser policy for authorization
- [ ] **Create Request:** Create AdminUserCreateRequest validation
- [ ] **Update Request:** Create AdminUserUpdateRequest validation

### tb_laporan_penjual
- [ ] **Migration:** Create migration file for tb_laporan_penjual table
- [ ] **Model:** Create SellerReport model with fillable, cast, and relation configurations
- [ ] **Factory:** Create SellerReport factory for testing
- [ ] **Seeder:** Create SellerReport seeder
- [ ] **Policy:** Create SellerReport policy for authorization
- [ ] **Create Request:** Create SellerReportCreateRequest validation
- [ ] **Update Request:** Create SellerReportUpdateRequest validation

### tb_laporan_penjualan
- [ ] **Migration:** Create migration file for tb_laporan_penjualan table
- [ ] **Model:** Create SalesReport model with fillable, cast, and relation configurations
- [ ] **Factory:** Create SalesReport factory for testing
- [ ] **Seeder:** Create SalesReport seeder
- [ ] **Policy:** Create SalesReport policy for authorization
- [ ] **Create Request:** Create SalesReportCreateRequest validation
- [ ] **Update Request:** Create SalesReportUpdateRequest validation

---

## System Settings Group

### Tables in this group:
- tb_pengaturan_sistem

### tb_pengaturan_sistem
- [ ] **Migration:** Create migration file for tb_pengaturan_sistem table
- [ ] **Model:** Create SystemSetting model with fillable, cast, and relation configurations
- [ ] **Factory:** Create SystemSetting factory for testing
- [ ] **Seeder:** Create SystemSetting seeder
- [ ] **Policy:** Create SystemSetting policy for authorization
- [ ] **Create Request:** Create SystemSettingCreateRequest validation
- [ ] **Update Request:** Create SystemSettingUpdateRequest validation

---

## Chat System Group

### Tables in this group:
- tb_chat_percakapan
- tb_chat_peserta_percakapan
- tb_chat_pesan_chat
- tb_chat_lampiran_pesan
- tb_chat_status_pesan
- tb_chat_reaksi_pesan
- tb_chat_edit_pesan
- tb_chat_referensi_produk_chat
- tb_chat_referensi_order_chat
- tb_chat_laporan_percakapan

### tb_chat_percakapan
- [ ] **Migration:** Create migration file for tb_chat_percakapan table
- [ ] **Model:** Create ChatConversation model with fillable, cast, and relation configurations
- [ ] **Factory:** Create ChatConversation factory for testing
- [ ] **Seeder:** Create ChatConversation seeder
- [ ] **Policy:** Create ChatConversation policy for authorization
- [ ] **Create Request:** Create ChatConversationCreateRequest validation
- [ ] **Update Request:** Create ChatConversationUpdateRequest validation

### tb_chat_peserta_percakapan
- [ ] **Migration:** Create migration file for tb_chat_peserta_percakapan table
- [ ] **Model:** Create ChatParticipant model with fillable, cast, and relation configurations
- [ ] **Factory:** Create ChatParticipant factory for testing
- [ ] **Seeder:** Create ChatParticipant seeder
- [ ] **Policy:** Create ChatParticipant policy for authorization
- [ ] **Create Request:** Create ChatParticipantCreateRequest validation
- [ ] **Update Request:** Create ChatParticipantUpdateRequest validation

### tb_chat_pesan_chat
- [ ] **Migration:** Create migration file for tb_chat_pesan_chat table
- [ ] **Model:** Create ChatMessage model with fillable, cast, and relation configurations
- [ ] **Factory:** Create ChatMessage factory for testing
- [ ] **Seeder:** Create ChatMessage seeder
- [ ] **Policy:** Create ChatMessage policy for authorization
- [ ] **Create Request:** Create ChatMessageCreateRequest validation
- [ ] **Update Request:** Create ChatMessageUpdateRequest validation

### tb_chat_lampiran_pesan
- [ ] **Migration:** Create migration file for tb_chat_lampiran_pesan table
- [ ] **Model:** Create MessageAttachment model with fillable, cast, and relation configurations
- [ ] **Factory:** Create MessageAttachment factory for testing
- [ ] **Seeder:** Create MessageAttachment seeder
- [ ] **Policy:** Create MessageAttachment policy for authorization
- [ ] **Create Request:** Create MessageAttachmentCreateRequest validation
- [ ] **Update Request:** Create MessageAttachmentUpdateRequest validation

### tb_chat_status_pesan
- [ ] **Migration:** Create migration file for tb_chat_status_pesan table
- [ ] **Model:** Create MessageStatus model with fillable, cast, and relation configurations
- [ ] **Factory:** Create MessageStatus factory for testing
- [ ] **Seeder:** Create MessageStatus seeder
- [ ] **Policy:** Create MessageStatus policy for authorization
- [ ] **Create Request:** Create MessageStatusCreateRequest validation
- [ ] **Update Request:** Create MessageStatusUpdateRequest validation

### tb_chat_reaksi_pesan
- [ ] **Migration:** Create migration file for tb_chat_reaksi_pesan table
- [ ] **Model:** Create MessageReaction model with fillable, cast, and relation configurations
- [ ] **Factory:** Create MessageReaction factory for testing
- [ ] **Seeder:** Create MessageReaction seeder
- [ ] **Policy:** Create MessageReaction policy for authorization
- [ ] **Create Request:** Create MessageReactionCreateRequest validation
- [ ] **Update Request:** Create MessageReactionUpdateRequest validation

### tb_chat_edit_pesan
- [ ] **Migration:** Create migration file for tb_chat_edit_pesan table
- [ ] **Model:** Create MessageEdit model with fillable, cast, and relation configurations
- [ ] **Factory:** Create MessageEdit factory for testing
- [ ] **Seeder:** Create MessageEdit seeder
- [ ] **Policy:** Create MessageEdit policy for authorization
- [ ] **Create Request:** Create MessageEditCreateRequest validation
- [ ] **Update Request:** Create MessageEditUpdateRequest validation

### tb_chat_referensi_produk_chat
- [ ] **Migration:** Create migration file for tb_chat_referensi_produk_chat table
- [ ] **Model:** Create ChatProductReference model with fillable, cast, and relation configurations
- [ ] **Factory:** Create ChatProductReference factory for testing
- [ ] **Seeder:** Create ChatProductReference seeder
- [ ] **Policy:** Create ChatProductReference policy for authorization
- [ ] **Create Request:** Create ChatProductReferenceCreateRequest validation
- [ ] **Update Request:** Create ChatProductReferenceUpdateRequest validation

### tb_chat_referensi_order_chat
- [ ] **Migration:** Create migration file for tb_chat_referensi_order_chat table
- [ ] **Model:** Create ChatOrderReference model with fillable, cast, and relation configurations
- [ ] **Factory:** Create ChatOrderReference factory for testing
- [ ] **Seeder:** Create ChatOrderReference seeder
- [ ] **Policy:** Create ChatOrderReference policy for authorization
- [ ] **Create Request:** Create ChatOrderReferenceCreateRequest validation
- [ ] **Update Request:** Create ChatOrderReferenceUpdateRequest validation

### tb_chat_laporan_percakapan
- [ ] **Migration:** Create migration file for tb_chat_laporan_percakapan table
- [ ] **Model:** Create ChatReport model with fillable, cast, and relation configurations
- [ ] **Factory:** Create ChatReport factory for testing
- [ ] **Seeder:** Create ChatReport seeder
- [ ] **Policy:** Create ChatReport policy for authorization
- [ ] **Create Request:** Create ChatReportCreateRequest validation
- [ ] **Update Request:** Create ChatReportUpdateRequest validation

---

## Master Address System Group

### Tables in this group:
- tb_master_provinsi
- tb_master_kota
- tb_master_kecamatan
- tb_master_kode_pos

### tb_master_provinsi
- [ ] **Migration:** Create migration file for tb_master_provinsi table
- [ ] **Model:** Create Province model with fillable, cast, and relation configurations
- [ ] **Factory:** Create Province factory for testing
- [ ] **Seeder:** Create Province seeder with real Indonesian provinces data
- [ ] **Policy:** Create Province policy for authorization
- [ ] **Create Request:** Create ProvinceCreateRequest validation
- [ ] **Update Request:** Create ProvinceUpdateRequest validation

### tb_master_kota
- [ ] **Migration:** Create migration file for tb_master_kota table
- [ ] **Model:** Create City model with fillable, cast, and relation configurations
- [ ] **Factory:** Create City factory for testing
- [ ] **Seeder:** Create City seeder with real Indonesian cities data
- [ ] **Policy:** Create City policy for authorization
- [ ] **Create Request:** Create CityCreateRequest validation
- [ ] **Update Request:** Create CityUpdateRequest validation

### tb_master_kecamatan
- [ ] **Migration:** Create migration file for tb_master_kecamatan table
- [ ] **Model:** Create District model with fillable, cast, and relation configurations
- [ ] **Factory:** Create District factory for testing
- [ ] **Seeder:** Create District seeder with real Indonesian districts data
- [ ] **Policy:** Create District policy for authorization
- [ ] **Create Request:** Create DistrictCreateRequest validation
- [ ] **Update Request:** Create DistrictUpdateRequest validation

### tb_master_kode_pos
- [ ] **Migration:** Create migration file for tb_master_kode_pos table
- [ ] **Model:** Create PostalCode model with fillable, cast, and relation configurations
- [ ] **Factory:** Create PostalCode factory for testing
- [ ] **Seeder:** Create PostalCode seeder with real Indonesian postal codes data
- [ ] **Policy:** Create PostalCode policy for authorization
- [ ] **Create Request:** Create PostalCodeCreateRequest validation
- [ ] **Update Request:** Create PostalCodeUpdateRequest validation

---

## Implementation Notes

### Model Implementation Requirements:
1. **Fillable attributes**: Include all non-primary key fields that should be mass-assignable
2. **Cast attributes**: Properly cast enum types, dates, timestamps, decimals, JSON fields
3. **Relationships**: Define appropriate Eloquent relationships (hasOne, hasMany, belongsTo, belongsToMany, etc.)
4. **Accessors/Mutators**: Format data appropriately for display or storage

### Migration Implementation Requirements:
1. **Field types**: Use appropriate MySQL data types matching DBML specification
2. **Constraints**: Include unique, nullable, and default value constraints
3. **Indexes**: Add appropriate indexes for performance
4. **Foreign keys**: Implement foreign key constraints where specified

### Request Validation Requirements:
1. **Validation rules**: Apply appropriate validation rules for each field
2. **Field types**: Validate data types and formats
3. **Business rules**: Include specific business rules for each feature

### Factory Implementation Requirements:
1. **Realistic data**: Generate meaningful sample data for testing
2. **Relationships**: Handle related model creation appropriately
3. **States**: Include any necessary factory states for different scenarios

### Seeder Implementation Requirements:
1. **Initial data**: Provide initial data needed for the system to function
2. **Realistic examples**: Include realistic examples for each entity
3. **Relationships**: Properly link related data in seeding process

## Implementation Order Recommendation

1. **System Setup**: Start with system settings and master address data
2. **User Management**: Implement user-related tables as they're foundational
3. **Product Management**: Build product and related tables
4. **Order Management**: Create cart, order, and shipping systems
5. **Payment System**: Implement payment processing tables
6. **Review System**: Add review and feedback mechanisms
7. **Notification System**: Create notification and activity tracking
8. **Admin & Reporting**: Set up admin and reporting features
9. **Chat System**: Finally implement the chat functionality

This order ensures all dependencies are met as the system develops.