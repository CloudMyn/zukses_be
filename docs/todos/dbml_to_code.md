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
- users
- penjual
- verifikasi_pengguna
- sesi_pengguna
- perangkat_pengguna

### users
- [x] **Migration:** Create migration file for users table
- [x] **Model:** Create User model with fillable, cast, and relation configurations
- [x] **Factory:** Create User factory for testing
- [x] **Seeder:** Create User seeder
- [x] **Policy:** Create User policy for authorization
- [x] **Create Request:** Create UserCreateRequest validation
- [x] **Update Request:** Create UserUpdateRequest validation

### penjual
- [x] **Migration:** Create migration file for penjual table
- [x] **Model:** Create Seller model with fillable, cast, and relation configurations
- [x] **Factory:** Create Seller factory for testing
- [x] **Seeder:** Create Seller seeder
- [x] **Policy:** Create Seller policy for authorization
- [x] **Create Request:** Create SellerCreateRequest validation
- [x] **Update Request:** Create SellerUpdateRequest validation

### verifikasi_pengguna
- [x] **Migration:** Create migration file for verifikasi_pengguna table
- [x] **Model:** Create Verification model with fillable, cast, and relation configurations
- [x] **Factory:** Create Verification factory for testing
- [x] **Seeder:** Create Verification seeder
- [x] **Policy:** Create Verification policy for authorization
- [x] **Create Request:** Create VerificationCreateRequest validation
- [x] **Update Request:** Create VerificationUpdateRequest validation

### sesi_pengguna
- [x] **Migration:** Create migration file for sesi_pengguna table
- [x] **Model:** Create Session model with fillable, cast, and relation configurations
- [x] **Factory:** Create Session factory for testing
- [x] **Seeder:** Create Session seeder
- [x] **Policy:** Create Session policy for authorization
- [x] **Create Request:** Create SessionCreateRequest validation
- [x] **Update Request:** Create SessionUpdateRequest validation

### perangkat_pengguna
- [x] **Migration:** Create migration file for perangkat_pengguna table
- [x] **Model:** Create Device model with fillable, cast, and relation configurations
- [x] **Factory:** Create Device factory for testing
- [x] **Seeder:** Create Device seeder
- [x] **Policy:** Create Device policy for authorization
- [x] **Create Request:** Create DeviceCreateRequest validation
- [x] **Update Request:** Create DeviceUpdateRequest validation

---

## Address Management Group

### Tables in this group:
- alamat

### alamat
- [x] **Migration:** Create migration file for alamat table
- [x] **Model:** Create Address model with fillable, cast, and relation configurations
- [x] **Factory:** Create Address factory for testing
- [x] **Seeder:** Create Address seeder
- [x] **Policy:** Create Address policy for authorization
- [x] **Create Request:** Create AddressCreateRequest validation
- [x] **Update Request:** Create AddressUpdateRequest validation

---

## Product Management Group

### Tables in this group:
- kategori_produk
- produk
- varian_produk
- nilai_varian_produk
- harga_varian_produk
- komposisi_harga_varian
- pengiriman_varian_produk
- gambar_produk
- info_pengiriman_produk
- log_inventori

### kategori_produk
- [x] **Migration:** Create migration file for kategori_produk table
- [x] **Model:** Create CategoryProduct model with fillable, cast, and relation configurations
- [x] **Factory:** Create CategoryProduct factory for testing
- [x] **Seeder:** Create CategoryProduct seeder
- [x] **Policy:** Create CategoryProduct policy for authorization
- [x] **Create Request:** Create CategoryProductCreateRequest validation
- [x] **Update Request:** Create CategoryProductUpdateRequest validation

### produk
- [x] **Migration:** Create migration file for produk table
- [x] **Model:** Create Product model with fillable, cast, and relation configurations
- [ ] **Factory:** Create Product factory for testing
- [ ] **Seeder:** Create Product seeder
- [ ] **Policy:** Create Product policy for authorization
- [ ] **Create Request:** Create ProductCreateRequest validation
- [ ] **Update Request:** Create ProductUpdateRequest validation

### varian_produk
- [x] **Migration:** Create migration file for varian_produk table
- [x] **Model:** Create ProductVariant model with fillable, cast, and relation configurations
- [x] **Factory:** Create ProductVariant factory for testing
- [x] **Seeder:** Create ProductVariant seeder
- [x] **Policy:** Create ProductVariant policy for authorization
- [ ] **Create Request:** Create ProductVariantCreateRequest validation
- [ ] **Update Request:** Create ProductVariantUpdateRequest validation

### nilai_varian_produk
- [x] **Migration:** Create migration file for nilai_varian_produk table
- [x] **Model:** Create ProductVariantValue model with fillable, cast, and relation configurations
- [x] **Factory:** Create ProductVariantValue factory for testing
- [x] **Seeder:** Create ProductVariantValue seeder
- [x] **Policy:** Create ProductVariantValue policy for authorization
- [x] **Create Request:** Create ProductVariantValueCreateRequest validation
- [x] **Update Request:** Create ProductVariantValueUpdateRequest validation

### harga_varian_produk
- [x] **Migration:** Create migration file for harga_varian_produk table
- [x] **Model:** Create ProductVariantPrice model with fillable, cast, and relation configurations
- [x] **Factory:** Create ProductVariantPrice factory for testing
- [x] **Seeder:** Create ProductVariantPrice seeder
- [x] **Policy:** Create ProductVariantPrice policy for authorization
- [x] **Create Request:** Create ProductVariantPriceCreateRequest validation
- [x] **Update Request:** Create ProductVariantPriceUpdateRequest validation

### komposisi_harga_varian
- [x] **Migration:** Create migration file for komposisi_harga_varian table
- [x] **Model:** Create VariantPriceComposition model with fillable, cast, and relation configurations
- [x] **Factory:** Create VariantPriceComposition factory for testing
- [x] **Seeder:** Create VariantPriceComposition seeder
- [x] **Policy:** Create VariantPriceComposition policy for authorization
- [x] **Create Request:** Create VariantPriceCompositionCreateRequest validation
- [x] **Update Request:** Create VariantPriceCompositionUpdateRequest validation

### pengiriman_varian_produk
- [x] **Migration:** Create migration file for pengiriman_varian_produk table
- [x] **Model:** Create VariantShippingInfo model with fillable, cast, and relation configurations
- [x] **Factory:** Create VariantShippingInfo factory for testing
- [x] **Seeder:** Create VariantShippingInfo seeder
- [x] **Policy:** Create VariantShippingInfo policy for authorization
- [x] **Create Request:** Create VariantShippingInfoCreateRequest validation
- [x] **Update Request:** Create VariantShippingInfoUpdateRequest validation

### gambar_produk
- [x] **Migration:** Create migration file for gambar_produk table
- [x] **Model:** Create ProductImage model with fillable, cast, and relation configurations
- [x] **Factory:** Create ProductImage factory for testing
- [x] **Seeder:** Create ProductImage seeder
- [x] **Policy:** Create ProductImage policy for authorization
- [ ] **Create Request:** Create ProductImageCreateRequest validation
- [ ] **Update Request:** Create ProductImageUpdateRequest validation

### info_pengiriman_produk
- [x] **Migration:** Create migration file for info_pengiriman_produk table
- [x] **Model:** Create ProductShippingInfo model with fillable, cast, and relation configurations
- [x] **Factory:** Create ProductShippingInfo factory for testing
- [x] **Seeder:** Create ProductShippingInfo seeder
- [x] **Policy:** Create ProductShippingInfo policy for authorization
- [ ] **Create Request:** Create ProductShippingInfoCreateRequest validation
- [ ] **Update Request:** Create ProductShippingInfoUpdateRequest validation

### log_inventori
- [x] **Migration:** Create migration file for log_inventori table
- [x] **Model:** Create InventoryLog model with fillable, cast, and relation configurations
- [x] **Factory:** Create InventoryLog factory for testing
- [x] **Seeder:** Create InventoryLog seeder
- [x] **Policy:** Create InventoryLog policy for authorization
- [ ] **Create Request:** Create InventoryLogCreateRequest validation
- [ ] **Update Request:** Create InventoryLogUpdateRequest validation

---

## Cart & Order Management Group

### Tables in this group:
- keranjang_belanja
- item_keranjang
- pesanan
- item_pesanan
- riwayat_status_pesanan

### keranjang_belanja
- [x] **Migration:** Create migration file for keranjang_belanja table
- [x] **Model:** Create Cart model with fillable, cast, and relation configurations
- [ ] **Factory:** Create Cart factory for testing
- [ ] **Seeder:** Create Cart seeder
- [ ] **Policy:** Create Cart policy for authorization
- [ ] **Create Request:** Create CartCreateRequest validation
- [ ] **Update Request:** Create CartUpdateRequest validation

### item_keranjang
- [x] **Migration:** Create migration file for item_keranjang table
- [x] **Model:** Create CartItem model with fillable, cast, and relation configurations
- [ ] **Factory:** Create CartItem factory for testing
- [ ] **Seeder:** Create CartItem seeder
- [ ] **Policy:** Create CartItem policy for authorization
- [ ] **Create Request:** Create CartItemCreateRequest validation
- [ ] **Update Request:** Create CartItemUpdateRequest validation

### pesanan
- [x] **Migration:** Create migration file for pesanan table
- [x] **Model:** Create Order model with fillable, cast, and relation configurations
- [ ] **Factory:** Create Order factory for testing
- [ ] **Seeder:** Create Order seeder
- [ ] **Policy:** Create Order policy for authorization
- [ ] **Create Request:** Create OrderCreateRequest validation
- [ ] **Update Request:** Create OrderUpdateRequest validation

### item_pesanan
- [x] **Migration:** Create migration file for item_pesanan table
- [x] **Model:** Create OrderItem model with fillable, cast, and relation configurations
- [ ] **Factory:** Create OrderItem factory for testing
- [ ] **Seeder:** Create OrderItem seeder
- [ ] **Policy:** Create OrderItem policy for authorization
- [ ] **Create Request:** Create OrderItemCreateRequest validation
- [ ] **Update Request:** Create OrderItemUpdateRequest validation

### riwayat_status_pesanan
- [ ] **Migration:** Create migration file for riwayat_status_pesanan table
- [ ] **Model:** Create OrderStatusHistory model with fillable, cast, and relation configurations
- [ ] **Factory:** Create OrderStatusHistory factory for testing
- [ ] **Seeder:** Create OrderStatusHistory seeder
- [ ] **Policy:** Create OrderStatusHistory policy for authorization
- [ ] **Create Request:** Create OrderStatusHistoryCreateRequest validation
- [ ] **Update Request:** Create OrderStatusHistoryUpdateRequest validation

---

## Shipping Management Group

### Tables in this group:
- metode_pengiriman
- metode_pengiriman_penjual
- tarif_pengiriman
- pengiriman_pesanan

### metode_pengiriman
- [x] **Migration:** Create migration file for metode_pengiriman table
- [x] **Model:** Create ShippingMethod model with fillable, cast, and relation configurations
- [ ] **Factory:** Create ShippingMethod factory for testing
- [ ] **Seeder:** Create ShippingMethod seeder
- [ ] **Policy:** Create ShippingMethod policy for authorization
- [ ] **Create Request:** Create ShippingMethodCreateRequest validation
- [ ] **Update Request:** Create ShippingMethodUpdateRequest validation

### metode_pengiriman_penjual
- [x] **Migration:** Create migration file for metode_pengiriman_penjual table
- [x] **Model:** Create SellerShippingMethod model with fillable, cast, and relation configurations
- [ ] **Factory:** Create SellerShippingMethod factory for testing
- [ ] **Seeder:** Create SellerShippingMethod seeder
- [ ] **Policy:** Create SellerShippingMethod policy for authorization
- [ ] **Create Request:** Create SellerShippingMethodCreateRequest validation
- [ ] **Update Request:** Create SellerShippingMethodUpdateRequest validation

### tarif_pengiriman
- [x] **Migration:** Create migration file for tarif_pengiriman table
- [x] **Model:** Create ShippingRate model with fillable, cast, and relation configurations
- [ ] **Factory:** Create ShippingRate factory for testing
- [ ] **Seeder:** Create ShippingRate seeder
- [ ] **Policy:** Create ShippingRate policy for authorization
- [ ] **Create Request:** Create ShippingRateCreateRequest validation
- [ ] **Update Request:** Create ShippingRateUpdateRequest validation

### pengiriman_pesanan
- [x] **Migration:** Create migration file for pengiriman_pesanan table
- [x] **Model:** Create OrderShipping model with fillable, cast, and relation configurations
- [ ] **Factory:** Create OrderShipping factory for testing
- [ ] **Seeder:** Create OrderShipping seeder
- [ ] **Policy:** Create OrderShipping policy for authorization
- [ ] **Create Request:** Create OrderShippingCreateRequest validation
- [ ] **Update Request:** Create OrderShippingUpdateRequest validation

---

## Payment Management Group

### Tables in this group:
- metode_pembayaran
- transaksi_pembayaran
- log_pembayaran

### metode_pembayaran
- [x] **Migration:** Create migration file for metode_pembayaran table
- [x] **Model:** Create PaymentMethod model with fillable, cast, and relation configurations
- [ ] **Factory:** Create PaymentMethod factory for testing
- [ ] **Seeder:** Create PaymentMethod seeder
- [ ] **Policy:** Create PaymentMethod policy for authorization
- [ ] **Create Request:** Create PaymentMethodCreateRequest validation
- [ ] **Update Request:** Create PaymentMethodUpdateRequest validation

### transaksi_pembayaran
- [x] **Migration:** Create migration file for transaksi_pembayaran table
- [x] **Model:** Create PaymentTransaction model with fillable, cast, and relation configurations
- [ ] **Factory:** Create PaymentTransaction factory for testing
- [ ] **Seeder:** Create PaymentTransaction seeder
- [ ] **Policy:** Create PaymentTransaction policy for authorization
- [ ] **Create Request:** Create PaymentTransactionCreateRequest validation
- [ ] **Update Request:** Create PaymentTransactionUpdateRequest validation

### log_pembayaran
- [x] **Migration:** Create migration file for log_pembayaran table
- [x] **Model:** Create PaymentLog model with fillable, cast, and relation configurations
- [ ] **Factory:** Create PaymentLog factory for testing
- [ ] **Seeder:** Create PaymentLog seeder
- [ ] **Policy:** Create PaymentLog policy for authorization
- [ ] **Create Request:** Create PaymentLogCreateRequest validation
- [ ] **Update Request:** Create PaymentLogUpdateRequest validation

---

## Review Management Group

### Tables in this group:
- ulasan_produk
- media_ulasan
- suara_ulasan

### ulasan_produk
- [x] **Migration:** Create migration file for ulasan_produk table
- [x] **Model:** Create ProductReview model with fillable, cast, and relation configurations
- [ ] **Factory:** Create ProductReview factory for testing
- [ ] **Seeder:** Create ProductReview seeder
- [ ] **Policy:** Create ProductReview policy for authorization
- [ ] **Create Request:** Create ProductReviewCreateRequest validation
- [ ] **Update Request:** Create ProductReviewUpdateRequest validation

### media_ulasan
- [x] **Migration:** Create migration file for media_ulasan table
- [x] **Model:** Create ReviewMedia model with fillable, cast, and relation configurations
- [ ] **Factory:** Create ReviewMedia factory for testing
- [ ] **Seeder:** Create ReviewMedia seeder
- [ ] **Policy:** Create ReviewMedia policy for authorization
- [ ] **Create Request:** Create ReviewMediaCreateRequest validation
- [ ] **Update Request:** Create ReviewMediaUpdateRequest validation

### suara_ulasan
- [x] **Migration:** Create migration file for suara_ulasan table
- [x] **Model:** Create ReviewVote model with fillable, cast, and relation configurations
- [ ] **Factory:** Create ReviewVote factory for testing
- [ ] **Seeder:** Create ReviewVote seeder
- [ ] **Policy:** Create ReviewVote policy for authorization
- [ ] **Create Request:** Create ReviewVoteCreateRequest validation
- [ ] **Update Request:** Create ReviewVoteUpdateRequest validation

---

## Notification & Activity Management Group

### Tables in this group:
- notifikasi_pengguna
- aktivitas_pengguna
- riwayat_pencarian

### notifikasi_pengguna
- [x] **Migration:** Create migration file for notifikasi_pengguna table
- [x] **Model:** Create UserNotification model with fillable, cast, and relation configurations
- [ ] **Factory:** Create UserNotification factory for testing
- [ ] **Seeder:** Create UserNotification seeder
- [ ] **Policy:** Create UserNotification policy for authorization
- [ ] **Create Request:** Create UserNotificationCreateRequest validation
- [ ] **Update Request:** Create UserNotificationUpdateRequest validation

### aktivitas_pengguna
- [x] **Migration:** Create migration file for aktivitas_pengguna table
- [x] **Model:** Create UserActivity model with fillable, cast, and relation configurations
- [ ] **Factory:** Create UserActivity factory for testing
- [ ] **Seeder:** Create UserActivity seeder
- [ ] **Policy:** Create UserActivity policy for authorization
- [ ] **Create Request:** Create UserActivityCreateRequest validation
- [ ] **Update Request:** Create UserActivityUpdateRequest validation

### riwayat_pencarian
- [x] **Migration:** Create migration file for riwayat_pencarian table
- [x] **Model:** Create SearchHistory model with fillable, cast, and relation configurations
- [ ] **Factory:** Create SearchHistory factory for testing
- [ ] **Seeder:** Create SearchHistory seeder
- [ ] **Policy:** Create SearchHistory policy for authorization
- [ ] **Create Request:** Create SearchHistoryCreateRequest validation
- [ ] **Update Request:** Create SearchHistoryUpdateRequest validation

---

## Admin & Reporting Group

### Tables in this group:
- pengguna_admin
- laporan_penjual
- laporan_penjualan

### pengguna_admin
- [x] **Migration:** Create migration file for pengguna_admin table
- [x] **Model:** Create AdminUser model with fillable, cast, and relation configurations
- [ ] **Factory:** Create AdminUser factory for testing
- [ ] **Seeder:** Create AdminUser seeder
- [ ] **Policy:** Create AdminUser policy for authorization
- [ ] **Create Request:** Create AdminUserCreateRequest validation
- [ ] **Update Request:** Create AdminUserUpdateRequest validation

### laporan_penjual
- [x] **Migration:** Create migration file for laporan_penjual table
- [x] **Model:** Create SellerReport model with fillable, cast, and relation configurations
- [ ] **Factory:** Create SellerReport factory for testing
- [ ] **Seeder:** Create SellerReport seeder
- [ ] **Policy:** Create SellerReport policy for authorization
- [ ] **Create Request:** Create SellerReportCreateRequest validation
- [ ] **Update Request:** Create SellerReportUpdateRequest validation

### laporan_penjualan
- [x] **Migration:** Create migration file for laporan_penjualan table
- [x] **Model:** Create SalesReport model with fillable, cast, and relation configurations
- [ ] **Factory:** Create SalesReport factory for testing
- [ ] **Seeder:** Create SalesReport seeder
- [ ] **Policy:** Create SalesReport policy for authorization
- [ ] **Create Request:** Create SalesReportCreateRequest validation
- [ ] **Update Request:** Create SalesReportUpdateRequest validation

---

## System Settings Group

### Tables in this group:
- pengaturan_sistem

### pengaturan_sistem
- [x] **Migration:** Create migration file for pengaturan_sistem table
- [x] **Model:** Create SystemSetting model with fillable, cast, and relation configurations
- [ ] **Factory:** Create SystemSetting factory for testing
- [ ] **Seeder:** Create SystemSetting seeder
- [ ] **Policy:** Create SystemSetting policy for authorization
- [ ] **Create Request:** Create SystemSettingCreateRequest validation
- [ ] **Update Request:** Create SystemSettingUpdateRequest validation

---

## Chat System Group

### Tables in this group:
- chat_percakapan
- chat_peserta_percakapan
- chat_pesan_chat
- chat_lampiran_pesan
- chat_status_pesan
- chat_reaksi_pesan
- chat_edit_pesan
- chat_referensi_produk_chat
- chat_referensi_order_chat
- chat_laporan_percakapan

### chat_percakapan
- [x] **Migration:** Create migration file for chat_percakapan table
- [x] **Model:** Create ChatConversation model with fillable, cast, and relation configurations
- [ ] **Factory:** Create ChatConversation factory for testing
- [ ] **Seeder:** Create ChatConversation seeder
- [ ] **Policy:** Create ChatConversation policy for authorization
- [ ] **Create Request:** Create ChatConversationCreateRequest validation
- [ ] **Update Request:** Create ChatConversationUpdateRequest validation

### chat_peserta_percakapan
- [x] **Migration:** Create migration file for chat_peserta_percakapan table
- [x] **Model:** Create ChatParticipant model with fillable, cast, and relation configurations
- [ ] **Factory:** Create ChatParticipant factory for testing
- [ ] **Seeder:** Create ChatParticipant seeder
- [ ] **Policy:** Create ChatParticipant policy for authorization
- [ ] **Create Request:** Create ChatParticipantCreateRequest validation
- [ ] **Update Request:** Create ChatParticipantUpdateRequest validation

### chat_pesan_chat
- [x] **Migration:** Create migration file for chat_pesan_chat table
- [x] **Model:** Create ChatMessage model with fillable, cast, and relation configurations
- [ ] **Factory:** Create ChatMessage factory for testing
- [ ] **Seeder:** Create ChatMessage seeder
- [ ] **Policy:** Create ChatMessage policy for authorization
- [ ] **Create Request:** Create ChatMessageCreateRequest validation
- [ ] **Update Request:** Create ChatMessageUpdateRequest validation

### chat_lampiran_pesan
- [x] **Migration:** Create migration file for chat_lampiran_pesan table
- [x] **Model:** Create MessageAttachment model with fillable, cast, and relation configurations
- [ ] **Factory:** Create MessageAttachment factory for testing
- [ ] **Seeder:** Create MessageAttachment seeder
- [ ] **Policy:** Create MessageAttachment policy for authorization
- [ ] **Create Request:** Create MessageAttachmentCreateRequest validation
- [ ] **Update Request:** Create MessageAttachmentUpdateRequest validation

### chat_status_pesan
- [x] **Migration:** Create migration file for chat_status_pesan table
- [x] **Model:** Create MessageStatus model with fillable, cast, and relation configurations
- [ ] **Factory:** Create MessageStatus factory for testing
- [ ] **Seeder:** Create MessageStatus seeder
- [ ] **Policy:** Create MessageStatus policy for authorization
- [ ] **Create Request:** Create MessageStatusCreateRequest validation
- [ ] **Update Request:** Create MessageStatusUpdateRequest validation

### chat_reaksi_pesan
- [x] **Migration:** Create migration file for chat_reaksi_pesan table
- [x] **Model:** Create MessageReaction model with fillable, cast, and relation configurations
- [ ] **Factory:** Create MessageReaction factory for testing
- [ ] **Seeder:** Create MessageReaction seeder
- [ ] **Policy:** Create MessageReaction policy for authorization
- [ ] **Create Request:** Create MessageReactionCreateRequest validation
- [ ] **Update Request:** Create MessageReactionUpdateRequest validation

### chat_edit_pesan
- [x] **Migration:** Create migration file for chat_edit_pesan table
- [x] **Model:** Create MessageEdit model with fillable, cast, and relation configurations
- [ ] **Factory:** Create MessageEdit factory for testing
- [ ] **Seeder:** Create MessageEdit seeder
- [ ] **Policy:** Create MessageEdit policy for authorization
- [ ] **Create Request:** Create MessageEditCreateRequest validation
- [ ] **Update Request:** Create MessageEditUpdateRequest validation

### chat_referensi_produk_chat
- [x] **Migration:** Create migration file for chat_referensi_produk_chat table
- [x] **Model:** Create ChatProductReference model with fillable, cast, and relation configurations
- [x] **Factory:** Create ChatProductReference factory for testing
- [x] **Seeder:** Create ChatProductReference seeder
- [x] **Policy:** Create ChatProductReference policy for authorization
- [ ] **Create Request:** Create ChatProductReferenceCreateRequest validation
- [ ] **Update Request:** Create ChatProductReferenceUpdateRequest validation

### chat_referensi_order_chat
- [x] **Migration:** Create migration file for chat_referensi_order_chat table
- [x] **Model:** Create ChatOrderReference model with fillable, cast, and relation configurations
- [x] **Factory:** Create ChatOrderReference factory for testing
- [x] **Seeder:** Create ChatOrderReference seeder
- [x] **Policy:** Create ChatOrderReference policy for authorization
- [ ] **Create Request:** Create ChatOrderReferenceCreateRequest validation
- [ ] **Update Request:** Create ChatOrderReferenceUpdateRequest validation

### chat_laporan_percakapan
- [x] **Migration:** Create migration file for chat_laporan_percakapan table
- [x] **Model:** Create ChatReport model with fillable, cast, and relation configurations
- [ ] **Factory:** Create ChatReport factory for testing
- [ ] **Seeder:** Create ChatReport seeder
- [ ] **Policy:** Create ChatReport policy for authorization
- [ ] **Create Request:** Create ChatReportCreateRequest validation
- [ ] **Update Request:** Create ChatReportUpdateRequest validation

---

## Master Address System Group

### Tables in this group:
- master_provinsi
- master_kota
- master_kecamatan
- master_kode_pos

### master_provinsi
- [x] **Migration:** Create migration file for master_provinsi table
- [x] **Model:** Create Province model with fillable, cast, and relation configurations
- [x] **Factory:** Create Province factory for testing
- [x] **Seeder:** Create Province seeder with real Indonesian provinces data
- [x] **Policy:** Create Province policy for authorization
- [x] **Create Request:** Create ProvinceCreateRequest validation
- [x] **Update Request:** Create ProvinceUpdateRequest validation

### master_kota
- [x] **Migration:** Create migration file for master_kota table
- [x] **Model:** Create City model with fillable, cast, and relation configurations
- [x] **Factory:** Create City factory for testing
- [x] **Seeder:** Create City seeder with real Indonesian cities data
- [x] **Policy:** Create City policy for authorization
- [x] **Create Request:** Create CityCreateRequest validation
- [x] **Update Request:** Create CityUpdateRequest validation

### master_kecamatan
- [x] **Migration:** Create migration file for master_kecamatan table
- [x] **Model:** Create District model with fillable, cast, and relation configurations
- [x] **Factory:** Create District factory for testing
- [x] **Seeder:** Create District seeder with real Indonesian districts data
- [x] **Policy:** Create District policy for authorization
- [x] **Create Request:** Create DistrictCreateRequest validation
- [x] **Update Request:** Create DistrictUpdateRequest validation

### master_kode_pos
- [x] **Migration:** Create migration file for master_kode_pos table
- [x] **Model:** Create PostalCode model with fillable, cast, and relation configurations
- [x] **Factory:** Create PostalCode factory for testing
- [x] **Seeder:** Create PostalCode seeder with real Indonesian postal codes data
- [x] **Policy:** Create PostalCode policy for authorization
- [x] **Create Request:** Create PostalCodeCreateRequest validation
- [x] **Update Request:** Create PostalCodeUpdateRequest validation

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
