# Missing API Endpoints Documentation Plan

## API Groups That Need Documentation

### 1. Seller Management API
- [ ] Sellers Resource (`apiResource('sellers', SellerController::class)`)

### 2. Device Management API
- [ ] Devices Resource (`apiResource('devices', DeviceController::class)`)
- [ ] Trust Device (`POST /api/devices/{device}/trust`)

### 3. Verification Management API
- [ ] Verifications Resource (`apiResource('verifications', VerificationController::class)`)

### 4. Session Management API
- [ ] Sessions Resource (`apiResource('sessions', SessionController::class)`)

### 5. Address & Location APIs
- [ ] Addresses Resource (`apiResource('addresses', AddressController::class)`)
- [ ] Provinces Resource (`apiResource('provinces', ProvinceController::class)`)
- [ ] Cities Resource (`apiResource('cities', CityController::class)`)
- [ ] Districts Resource (`apiResource('districts', DistrictController::class)`)
- [ ] Postal Codes Resource (`apiResource('postal-codes', PostalCodeController::class)`)

### 6. Product Management APIs (Additional Resources)
- [ ] Product Variants Resource (`apiResource('product-variants', ProductVariantController::class)`)
- [ ] Product Variant Values Resource (`apiResource('product-variant-values', ProductVariantValueController::class)`)
- [ ] Product Variant Prices Resource (`apiResource('product-variant-prices', ProductVariantPriceController::class)`)
- [ ] Product Images Resource (`apiResource('product-images', ProductImageController::class)`)
- [ ] Product Shipping Info Resource (`apiResource('product-shipping-info', ProductShippingInfoController::class)`)
- [ ] Inventory Logs Resource (`apiResource('inventory-logs', InventoryLogController::class)`)
- [ ] Variant Price Compositions Resource (`apiResource('variant-price-compositions', VariantPriceCompositionController::class)`)
- [ ] Variant Shipping Info Resource (`apiResource('variant-shipping-info', VariantShippingInfoController::class)`)

### 7. Shopping & Order APIs
- [ ] Carts Resource (`apiResource('carts', CartController::class)`)
- [ ] Cart Items Resource (`apiResource('cart-items', CartItemController::class)`)
- [ ] Orders Resource (`apiResource('orders', OrderController::class)`)
- [ ] Order Items Resource (`apiResource('order-items', OrderItemController::class)`)
- [ ] Order Status History Resource (`apiResource('order-status-history', OrderStatusHistoryController::class)`)

### 8. Payment & Shipping APIs
- [ ] Shipping Methods Resource (`apiResource('shipping-methods', ShippingMethodController::class)`)
- [ ] Shipping Rates Resource (`apiResource('shipping-rates', ShippingRateController::class)`)
- [ ] Order Shipping Resource (`apiResource('order-shipping', OrderShippingController::class)`)
- [ ] Payment Methods Resource (`apiResource('payment-methods', PaymentMethodController::class)`)
- [ ] Payment Transactions Resource (`apiResource('payment-transactions', PaymentTransactionController::class)`)
- [ ] Payment Logs Resource (`apiResource('payment-logs', PaymentLogController::class)`)

### 9. Review & Feedback APIs
- [ ] Product Reviews Resource (`apiResource('product-reviews', ProductReviewController::class)`)
- [ ] Review Media Resource (`apiResource('review-media', ReviewMediaController::class)`)
- [ ] Review Votes Resource (`apiResource('review-votes', ReviewVoteController::class)`)

### 10. Notification & Activity APIs
- [ ] Notifications Resource (`apiResource('notifications', UserNotificationController::class)`)
- [ ] Activities Resource (`apiResource('activities', UserActivityController::class)`)
- [ ] Search History Resource (`apiResource('search-history', SearchHistoryController::class)`)

### 11. Admin & Reporting APIs
- [ ] Admin Users Resource (`apiResource('admin-users', AdminController::class)`)
- [ ] Seller Reports Resource (`apiResource('seller-reports', SellerReportController::class)`)
- [ ] Sales Reports Resource (`apiResource('sales-reports', SalesReportController::class)`)

### 12. Chat System APIs
- [ ] Chat Conversations Resource (`apiResource('chat-conversations', ChatConversationController::class)`)
- [ ] Chat Participants Resource (`apiResource('chat-participants', ChatParticipantController::class)`)
- [ ] Chat Messages Resource (`apiResource('chat-messages', ChatMessageController::class)`)
- [ ] Message Statuses Resource (`apiResource('message-statuses', MessageStatusController::class)`)
- [ ] Message Reactions Resource (`apiResource('message-reactions', MessageReactionController::class)`)
- [ ] Message Edits Resource (`apiResource('message-edits', MessageEditController::class)`)
- [ ] Message Attachments Resource (`apiResource('message-attachments', MessageAttachmentController::class)`)
- [ ] Chat Product References Resource (`apiResource('chat-product-references', ChatProductReferenceController::class)`)
- [ ] Chat Order References Resource (`apiResource('chat-order-references', ChatOrderReferenceController::class)`)
- [ ] Chat Reports Resource (`apiResource('chat-reports', ChatReportController::class)`)

### 13. System Settings API
- [ ] System Settings Resource (`apiResource('system-settings', SystemSettingController::class)`)

## Implementation Steps

1. For each API group, create a section in the API_Documentation.md file
2. Document the base endpoint and authentication requirements
3. For each resource endpoint, document:
   - HTTP method
   - Endpoint path
   - Authentication requirements
   - Request headers
   - Request body parameters (with validation rules)
   - Query parameters (if applicable)
   - Path parameters (if applicable)
   - Response format for success cases
   - Response format for error cases
   - Example requests and responses
4. Ensure all parameters are documented with proper data types, required status, and descriptions
5. Include all validation rules for each parameter
6. Document all possible HTTP status codes and their meanings
7. Follow the existing documentation style and format