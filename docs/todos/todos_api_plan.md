# Comprehensive API Documentation Plan - Zukses E-commerce Platform

## Documentation Structure
Create a detailed documentation section for each API group following the existing format in the API_Documentation.md file.

## API Groups to Document

### 1. Authentication API (Already partially documented)
- [ ] Registration (`POST /api/auth/register`)
- [ ] Login (`POST /api/auth/login`)
- [ ] Send OTP (`POST /api/auth/send-otp`)
- [ ] Forgot Password (`POST /api/auth/forgot-password`)
- [ ] Verify OTP (`POST /api/auth/verify-otp`)
- [ ] Reset Password (`POST /api/auth/reset-password`)
- [ ] Google Authentication (`GET /api/auth/google`)
- [ ] Google Callback (`GET /api/auth/google/callback`)
- [ ] Logout (`POST /api/auth/logout`)
- [ ] Get Current User (`GET /api/auth/me`)
- [ ] Update Profile (`PUT /api/auth/profile`)
- [ ] Delete Account (`DELETE /api/auth/delete-account`)
- [ ] Show Profile (`GET /api/auth/profile`)

### 2. User Management API (Already documented)
- [ ] Users Resource (`apiResource('users', UserController::class)`)

### 3. Seller Management API
- [ ] Sellers Resource (`apiResource('sellers', SellerController::class)`)

### 4. Device Management API
- [ ] Devices Resource (`apiResource('devices', DeviceController::class)`)
- [ ] Trust Device (`POST /api/devices/{device}/trust`)

### 5. Verification Management API
- [ ] Verifications Resource (`apiResource('verifications', VerificationController::class)`)

### 6. Session Management API
- [ ] Sessions Resource (`apiResource('sessions', SessionController::class)`)

### 7. Address & Location APIs
- [ ] Addresses Resource (`apiResource('addresses', AddressController::class)`)
- [ ] Provinces Resource (`apiResource('provinces', ProvinceController::class)`)
- [ ] Cities Resource (`apiResource('cities', CityController::class)`)
- [ ] Districts Resource (`apiResource('districts', DistrictController::class)`)
- [ ] Postal Codes Resource (`apiResource('postal-codes', PostalCodeController::class)`)

### 8. Product Management APIs
- [ ] Categories Resource (`apiResource('categories', CategoryProductController::class)`)
- [ ] Products Resource (`apiResource('products', ProductController::class)`)
- [ ] Product Variants Resource (`apiResource('product-variants', ProductVariantController::class)`)
- [ ] Product Variant Values Resource (`apiResource('product-variant-values', ProductVariantValueController::class)`)
- [ ] Product Variant Prices Resource (`apiResource('product-variant-prices', ProductVariantPriceController::class)`)
- [ ] Product Images Resource (`apiResource('product-images', ProductImageController::class)`)
- [ ] Product Shipping Info Resource (`apiResource('product-shipping-info', ProductShippingInfoController::class)`)
- [ ] Inventory Logs Resource (`apiResource('inventory-logs', InventoryLogController::class)`)
- [ ] Variant Price Compositions Resource (`apiResource('variant-price-compositions', VariantPriceCompositionController::class)`)
- [ ] Variant Shipping Info Resource (`apiResource('variant-shipping-info', VariantShippingInfoController::class)`)

### 9. Shopping & Order APIs
- [ ] Carts Resource (`apiResource('carts', CartController::class)`)
- [ ] Cart Items Resource (`apiResource('cart-items', CartItemController::class)`)
- [ ] Orders Resource (`apiResource('orders', OrderController::class)`)
- [ ] Order Items Resource (`apiResource('order-items', OrderItemController::class)`)
- [ ] Order Status History Resource (`apiResource('order-status-history', OrderStatusHistoryController::class)`)

### 10. Payment & Shipping APIs
- [ ] Shipping Methods Resource (`apiResource('shipping-methods', ShippingMethodController::class)`)
- [ ] Shipping Rates Resource (`apiResource('shipping-rates', ShippingRateController::class)`)
- [ ] Order Shipping Resource (`apiResource('order-shipping', OrderShippingController::class)`)
- [ ] Payment Methods Resource (`apiResource('payment-methods', PaymentMethodController::class)`)
- [ ] Payment Transactions Resource (`apiResource('payment-transactions', PaymentTransactionController::class)`)
- [ ] Payment Logs Resource (`apiResource('payment-logs', PaymentLogController::class)`)

### 11. Review & Feedback APIs
- [ ] Product Reviews Resource (`apiResource('product-reviews', ProductReviewController::class)`)
- [ ] Review Media Resource (`apiResource('review-media', ReviewMediaController::class)`)
- [ ] Review Votes Resource (`apiResource('review-votes', ReviewVoteController::class)`)

### 12. Notification & Activity APIs
- [ ] Notifications Resource (`apiResource('notifications', UserNotificationController::class)`)
- [ ] Activities Resource (`apiResource('activities', UserActivityController::class)`)
- [ ] Search History Resource (`apiResource('search-history', SearchHistoryController::class)`)

### 13. Admin & Reporting APIs
- [ ] Admin Users Resource (`apiResource('admin-users', AdminController::class)`)
- [ ] Seller Reports Resource (`apiResource('seller-reports', SellerReportController::class)`)
- [ ] Sales Reports Resource (`apiResource('sales-reports', SalesReportController::class)`)

### 14. Chat System APIs
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

### 15. System Settings API
- [ ] System Settings Resource (`apiResource('system-settings', SystemSettingController::class)`)

## Documentation Format for Each API Group

For each API group, document:

1. **Base Endpoint**: The resource endpoint with authentication requirements
2. **Endpoints**: Each HTTP method and endpoint with:
   - Request parameters (query, path, body)
   - Request body fields with validation rules
   - Response format and status codes
   - Error scenarios
3. **Brief description** of the API's purpose and functionality

## Implementation Guidelines

1. Follow the existing format in the documentation file
2. Document all standard HTTP methods (GET, POST, PUT, PATCH, DELETE) for API resources
3. Include any custom endpoints
4. Provide example requests and responses
5. Include authentication requirements for each endpoint
6. List validation rules for request parameters
7. Document possible error responses and their causes

## Priority Order

1. Authentication API (update existing documentation)
2. User & Seller Management APIs
3. Product Management APIs
4. Shopping & Order APIs
5. All other APIs in sequence