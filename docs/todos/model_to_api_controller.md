# API Controller Implementation Roadmap

This document outlines the step-by-step plan for implementing API controllers from the existing models and registering them in the routes/api.php file.

## Implementation Order

### Phase 1: Core User Management APIs
1. UserController - User management endpoints
2. SellerController - Seller management endpoints
3. DeviceController - Device management endpoints
4. VerificationController - Verification management endpoints
5. SessionController - Session management endpoints

### Phase 2: Address & Location APIs
1. AddressController - Address management endpoints
2. ProvinceController - Province management endpoints
3. CityController - City management endpoints
4. DistrictController - District management endpoints
5. PostalCodeController - Postal code management endpoints

### Phase 3: Product Management APIs
1. CategoryProductController - Category management endpoints
2. ProductController - Product management endpoints
3. ProductVariantController - Product variant management endpoints
4. ProductVariantValueController - Product variant value management endpoints
5. ProductVariantPriceController - Product variant price management endpoints
6. ProductImageController - Product image management endpoints
7. ProductShippingInfoController - Product shipping info management endpoints
8. InventoryLogController - Inventory log management endpoints
9. VariantPriceCompositionController - Variant price composition management endpoints
10. VariantShippingInfoController - Variant shipping info management endpoints

### Phase 4: Shopping & Order APIs
1. CartController - Shopping cart management endpoints
2. CartItemController - Cart item management endpoints
3. OrderController - Order management endpoints
4. OrderItemController - Order item management endpoints
5. OrderStatusHistoryController - Order status history management endpoints

### Phase 5: Payment & Shipping APIs
1. ShippingMethodController - Shipping method management endpoints
2. ShippingRateController - Shipping rate management endpoints
3. OrderShippingController - Order shipping management endpoints
4. PaymentMethodController - Payment method management endpoints
5. PaymentTransactionController - Payment transaction management endpoints
6. PaymentLogController - Payment log management endpoints

### Phase 6: Review & Feedback APIs
1. ProductReviewController - Product review management endpoints
2. ReviewMediaController - Review media management endpoints
3. ReviewVoteController - Review vote management endpoints

### Phase 7: Notification & Activity APIs
1. UserNotificationController - User notification management endpoints
2. UserActivityController - User activity management endpoints
3. SearchHistoryController - Search history management endpoints

### Phase 8: Admin & Reporting APIs
1. AdminController - Admin management endpoints
2. SellerReportController - Seller report management endpoints
3. SalesReportController - Sales report management endpoints

### Phase 9: Chat System APIs
1. ChatConversationController - Chat conversation management endpoints
2. ChatParticipantController - Chat participant management endpoints
3. ChatMessageController - Chat message management endpoints
4. MessageStatusController - Message status management endpoints
5. MessageReactionController - Message reaction management endpoints
6. MessageEditController - Message edit management endpoints
7. MessageAttachmentController - Message attachment management endpoints
8. ChatProductReferenceController - Chat product reference management endpoints
9. ChatOrderReferenceController - Chat order reference management endpoints
10. ChatReportController - Chat report management endpoints

### Phase 10: System Settings APIs
1. SystemSettingController - System settings management endpoints

## Implementation Steps for Each Controller

### 1. Create Controller
- Use `php artisan make:controller API/{ControllerName} --api`
- Add appropriate namespace for API controllers
- Implement resource methods: index, show, store, update, destroy
- Add authorization checks using policies
- Apply request validation using form request classes

### 2. Register Routes in routes/api.php
- Group routes by resource (e.g., '/api/users', '/api/products')
- Apply appropriate middleware
- Use resource routes where applicable
- Follow RESTful naming conventions

### 3. Add Request Validation
- Create form request classes for store and update operations
- Validate input data according to model requirements
- Include authorization checks in form requests

### 4. Add API Resources (if needed)
- Create API resources for complex data transformations
- Format responses appropriately
- Include relationships where necessary

### 5. Add API Documentation
- Document each endpoint with OpenAPI/Swagger annotations
- Include example requests and responses
- Add authentication requirements

## Required Middleware

### Authentication
- Apply `auth:sanctum` middleware to protected routes
- Some endpoints may be public (e.g., product listings, category lists)

### Rate Limiting
- Apply `throttle` middleware to prevent API abuse

### CORS
- Configure CORS for frontend integration

## Authorization Implementation

### Using Laravel Policies
- Each model should have a corresponding policy
- Controllers will use `authorize()` method to check permissions
- Policies will define who can access which actions

### Access Levels
- ADMIN: Full access to all features
- PEDAGANG: Access to seller-specific features and their products/orders
- PELANGGAN: Access to customer features
- Guest: Limited access to public information

## API Response Format

All API responses should follow a consistent format:

```json
{
  "success": true,
  "message": "Success message",
  "data": {},
  "pagination": {}
}
```

For errors:
```json
{
  "success": false,
  "message": "Error message",
  "errors": {}
}
```

## Error Handling

- Implement proper HTTP status codes
- Handle validation errors gracefully
- Include meaningful error messages
- Log server errors appropriately

## Testing

- Each controller should have corresponding API tests
- Test all CRUD operations where applicable
- Test authorization and authentication
- Test edge cases and error conditions