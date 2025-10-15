# TODO: API Endpoint Testing Implementation Plan

## Overview

Comprehensive testing plan for all 52 API endpoints organized by functional phases. This plan covers unit tests, feature tests, and integration testing scenarios.

## Testing Prerequisites

### 1. Environment Setup
```bash
# Install testing dependencies
composer require --dev phpunit/phpunit
composer require --dev laravel/telescope
composer require --dev fakerphp/faker

# Create testing environment configuration
cp .env .env.testing
```

### 2. Dedicated Testing Database Setup ⚠️ **CRITICAL**

#### Step 1: Create Separate Testing Database
```bash
# Create dedicated testing database (MySQL)
mysql -u root -p
CREATE DATABASE zukses_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'zukses_test'@'localhost' IDENTIFIED BY 'secure_test_password';
GRANT ALL PRIVILEGES ON zukses_test.* TO 'zukses_test'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### Step 2: Configure .env.testing
```bash
# Edit .env.testing file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=zukses_test
DB_USERNAME=zukses_test
DB_PASSWORD=secure_test_password

# Ensure testing environment is set
APP_ENV=testing
APP_DEBUG=true
LOG_CHANNEL=stack

# Use separate cache for testing
CACHE_DRIVER=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync

# Disable external services for testing
MAIL_MAILER=array
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

# Use test storage
FILESYSTEM_DRIVER=local
```

#### Step 3: Database Migration & Seeding
```bash
# Run migrations on testing database ONLY
php artisan migrate:fresh --env=testing --force

# Seed test data
php artisan db:seed --env=testing --class=DatabaseSeeder --force

# Verify database connection
php artisan tinker --env=testing
# Test: DB::connection()->getDatabaseName()
```

### 3. Testing Configuration Files

#### Create phpunit.xml (if not exists)
```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="./vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true">
    <testsuites>
        <testsuite name="Unit">
            <directory suffix="Test.php">./tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory suffix="Test.php">./tests/Feature</directory>
        </testsuite>
    </testsuites>
    <coverage processUncoveredFiles="true">
        <include>
            <directory suffix=".php">./app</directory>
        </include>
        <exclude>
            <directory suffix=".php">./app/Console/Kernel.php</directory>
            <directory suffix=".php">./app/Exceptions</directory>
        </exclude>
    </coverage>
    <php>
        <server name="APP_ENV" value="testing"/>
        <server name="BCRYPT_ROUNDS" value="4"/>
        <server name="CACHE_DRIVER" value="array"/>
        <server name="DB_CONNECTION" value="mysql"/>
        <server name="DB_DATABASE" value="zukses_test"/>
        <server name="DB_HOST" value="127.0.0.1"/>
        <server name="DB_PASSWORD" value="secure_test_password"/>
        <server name="DB_PORT" value="3306"/>
        <server name="DB_USERNAME" value="zukses_test"/>
        <server name="MAIL_MAILER" value="array"/>
        <server name="QUEUE_CONNECTION" value="sync"/>
        <server name="SESSION_DRIVER" value="array"/>
        <server name="TELESCOPE_ENABLED" value="false"/>
    </php>
</phpunit>
```

### 4. Authentication Setup for Testing
```bash
# Generate JWT secret for testing environment
php artisan jwt:secret --env=testing

# Create test users with different roles
php artisan tinker --env=testing

# Create test admin user
$admin = \App\Models\User::create([
    'username' => 'test_admin',
    'email' => 'admin@test.com',
    'kata_sandi' => Hash::make('password123'),
    'tipe_user' => 'ADMIN',
    'status' => 'AKTIF'
]);

# Create test seller user
$seller = \App\Models\User::create([
    'username' => 'test_seller',
    'email' => 'seller@test.com',
    'kata_sandi' => Hash::make('password123'),
    'tipe_user' => 'PEDAGANG',
    'status' => 'AKTIF'
]);

# Create test customer user
$customer = \App\Models\User::create([
    'username' => 'test_customer',
    'email' => 'customer@test.com',
    'kata_sandi' => Hash::make('password123'),
    'tipe_user' => 'PELANGGAN',
    'status' => 'AKTIF'
]);
```

### 5. Create Test Data Seeders
```bash
# Create test data seeder
php artisan make:seeder TestDataSeeder

# Edit database/seeders/TestDataSeeder.php
```

#### TestDataSeeder.php Example:
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Seller;
use App\Models\CategoryProduct;
use App\Models\Product;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Create test categories
        $categories = CategoryProduct::factory()->count(5)->create();

        // Create test sellers with products
        User::where('tipe_user', 'PEDAGANG')->get()->each(function ($user) use ($categories) {
            $seller = Seller::factory()->create(['id_user' => $user->id]);

            // Create products for each seller
            Product::factory()->count(10)->create([
                'id_seller' => $seller->id,
                'id_kategori' => $categories->random()->id
            ]);
        });

        // Create test addresses
        User::all()->each(function ($user) {
            \App\Models\Address::factory()->count(2)->create(['id_user' => $user->id]);
        });
    }
}
```

### 6. Verify Testing Environment
```bash
# Verify database connection
php artisan tinker --env=testing
> DB::connection()->getDatabaseName();
// Should return: "zukses_test"

# Verify test data exists
php artisan tinker --env=testing
> \App\Models\User::count();
// Should return test users count

> \App\Models\Product::count();
// Should return test products count
```

### 7. Database Isolation Verification
```bash
# Test that main database is NOT affected
php artisan tinker  # Production/Development environment
> \App\Models\User::where('email', 'admin@test.com')->first();
// Should return null (test user only in test database)

# Test that test database IS populated
php artisan tinker --env=testing
> \App\Models\User::where('email', 'admin@test.com')->first();
// Should return test admin user
```

## Phase 1: Authentication Endpoints Testing

### 1.1 Auth Controller Tests
**Controller:** `AuthController`

#### Test Scenarios:
```php
// tests/Feature/Auth/AuthControllerTest.php

1. **User Registration**
   - [ ] POST /api/auth/register - Valid data
   - [ ] POST /api/auth/register - Invalid email
   - [ ] POST /api/auth/register - Duplicate email
   - [ ] POST /api/auth/register - Missing required fields
   - [ ] POST /api/auth/register - Invalid phone number

2. **User Login**
   - [ ] POST /api/auth/login - Valid credentials
   - [ ] POST /api/auth/login - Invalid credentials
   - [ ] POST /api/auth/login - Non-existent user
   - [ ] POST /api/auth/login - Inactive user

3. **OTP Operations**
   - [ ] POST /api/auth/send-otp - Valid phone
   - [ ] POST /api/auth/send-otp - Invalid phone
   - [ ] POST /api/auth/verify-otp - Valid OTP
   - [ ] POST /api/auth/verify-otp - Invalid OTP
   - [ ] POST /api/auth/verify-otp - Expired OTP

4. **Password Recovery**
   - [ ] POST /api/auth/forgot-password - Valid email
   - [ ] POST /api/auth/forgot-password - Invalid email
   - [ ] POST /api/auth/reset-password - Valid token
   - [ ] POST /api/auth/reset-password - Invalid token

5. **Google Authentication**
   - [ ] GET /api/auth/google - Redirect to Google
   - [ ] GET /api/auth/google/callback - Successful callback
   - [ ] GET /api/auth/google/callback - Failed callback

6. **Authenticated Operations**
   - [ ] POST /api/auth/logout - Valid token
   - [ ] POST /api/auth/logout - Invalid token
   - [ ] GET /api/auth/me - Valid token
   - [ ] GET /api/auth/me - Invalid token
```

#### Testing Commands:
```bash
# Run auth tests
php artisan test tests/Feature/Auth/AuthControllerTest.php

# Run specific auth test
php artisan test --filter test_user_registration_with_valid_data

# Generate auth test coverage report
php artisan test --coverage tests/Feature/Auth/
```

## Phase 2: Core User Management API Testing

### 2.1 User Controller Tests
**Controller:** `UserController`

#### Test Scenarios:
```php
// tests/Feature/Api/UserControllerTest.php

1. **Index Operations**
   - [ ] GET /api/users - List all users (admin)
   - [ ] GET /api/users - List users (regular user - forbidden)
   - [ ] GET /api/users - Pagination test
   - [ ] GET /api/users - Search functionality
   - [ ] GET /api/users - Filter by status

2. **Store Operations**
   - [ ] POST /api/users - Create user (valid data)
   - [ ] POST /api/users - Create user (duplicate email)
   - [ ] POST /api/users - Create user (invalid data)
   - [ ] POST /api/users - Create user (missing required fields)

3. **Show Operations**
   - [ ] GET /api/users/{id} - Get own profile
   - [ ] GET /api/users/{id} - Get other user profile
   - [ ] GET /api/users/{id} - Non-existent user

4. **Update Operations**
   - [ ] PUT /api/users/{id} - Update own profile
   - [ ] PUT /api/users/{id} - Update other user (admin)
   - [ ] PUT /api/users/{id} - Update with invalid data

5. **Destroy Operations**
   - [ ] DELETE /api/users/{id} - Delete own account
   - [ ] DELETE /api/users/{id} - Delete user (admin)
   - [ ] DELETE /api/users/{id} - Non-existent user
```

#### Testing Commands:
```bash
# Run user management tests
php artisan test tests/Feature/Api/UserControllerTest.php

# Run with database transactions
php artisan test --testsuite=Feature --env=testing
```

### 2.2 Seller Controller Tests
**Controller:** `SellerController`

#### Test Scenarios:
```php
// tests/Feature/Api/SellerControllerTest.php

1. **CRUD Operations**
   - [ ] GET /api/sellers - List sellers
   - [ ] POST /api/sellers - Create seller profile
   - [ ] GET /api/sellers/{id} - Get seller details
   - [ ] PUT /api/sellers/{id} - Update seller profile
   - [ ] DELETE /api/sellers/{id} - Delete seller profile

2. **Seller-Specific Features**
   - [ ] GET /api/sellers/{id}/products - Get seller products
   - [ ] GET /api/sellers/{id}/reviews - Get seller reviews
   - [ ] GET /api/sellers/{id}/ratings - Get seller ratings
```

### 2.3 Device & Verification Controllers

#### Device Controller Tests
```php
// tests/Feature/Api/DeviceControllerTest.php

1. **Device Management**
   - [ ] GET /api/devices - List user devices
   - [ ] POST /api/devices - Register new device
   - [ ] PUT /api/devices/{id} - Update device info
   - [ ] DELETE /api/devices/{id} - Remove device
   - [ ] POST /api/devices/{id}/trust - Mark device as trusted
```

#### Verification Controller Tests
```php
// tests/Feature/Api/VerificationControllerTest.php

1. **Verification Processes**
   - [ ] GET /api/verifications - List verification attempts
   - [ ] POST /api/verifications - Create verification request
   - [ ] PUT /api/verifications/{id} - Update verification status
   - [ ] GET /api/verifications/{id} - Get verification details
```

## Phase 3: Address & Location APIs Testing

### 3.1 Address Controller Tests
**Controller:** `AddressController`

#### Test Scenarios:
```php
// tests/Feature/Api/AddressControllerTest.php

1. **Address CRUD**
   - [ ] GET /api/addresses - List user addresses
   - [ ] POST /api/addresses - Add new address
   - [ ] GET /api/addresses/{id} - Get address details
   - [ ] PUT /api/addresses/{id} - Update address
   - [ ] DELETE /api/addresses/{id} - Delete address

2. **Address Features**
   - [ ] PUT /api/addresses/{id}/primary - Set as primary address
   - [ ] GET /api/addresses/primary - Get primary address
   - [ ] POST /api/addresses/validate - Validate address format
```

### 3.2 Geographic Data Tests

#### Province, City, District, Postal Code Controllers
```php
// tests/Feature/Api/LocationControllerTest.php

1. **Location Data**
   - [ ] GET /api/provinces - List all provinces
   - [ ] GET /api/provinces/{id} - Get province details
   - [ ] GET /api/cities - List all cities
   - [ ] GET /api/cities?province_id={id} - Cities by province
   - [ ] GET /api/districts - List all districts
   - [ ] GET /api/districts?city_id={id} - Districts by city
   - [ ] GET /api/postal-codes - List postal codes
   - [ ] GET /api/postal-codes?district_id={id} - Postal codes by district
```

## Phase 4: Product Management APIs Testing

### 4.1 Product Controller Tests
**Controller:** `ProductController`

#### Test Scenarios:
```php
// tests/Feature/Api/ProductControllerTest.php

1. **Product CRUD**
   - [ ] GET /api/products - List products (with filters)
   - [ ] POST /api/products - Create product (seller)
   - [ ] GET /api/products/{id} - Get product details
   - [ ] PUT /api/products/{id} - Update product (owner)
   - [ ] DELETE /api/products/{id} - Delete product (owner)

2. **Product Search & Filter**
   - [ ] GET /api/products?search={keyword} - Search products
   - [ ] GET /api/products?category={id} - Filter by category
   - [ ] GET /api/products?seller={id} - Filter by seller
   - [ ] GET /api/products?min_price={price} - Price range filter
   - [ ] GET /api/products?max_price={price} - Price range filter

3. **Product Features**
   - [ ] GET /api/products/{id}/variants - Get product variants
   - [ ] GET /api/products/{id}/reviews - Get product reviews
   - [ ] GET /api/products/{id}/images - Get product images
   - [ ] POST /api/products/{id}/favorite - Add to favorites
   - [ ] DELETE /api/products/{id}/favorite - Remove from favorites
```

### 4.2 Product Variant Tests

#### Product Variant Controller
```php
// tests/Feature/Api/ProductVariantControllerTest.php

1. **Variant Management**
   - [ ] GET /api/product-variants - List variants
   - [ ] POST /api/product-variants - Create variant
   - [ ] PUT /api/product-variants/{id} - Update variant
   - [ ] DELETE /api/product-variants/{id} - Delete variant
```

#### Product Variant Value Controller
```php
// tests/Feature/Api/ProductVariantValueControllerTest.php

1. **Variant Values**
   - [ ] GET /api/product-variant-values - List variant values
   - [ ] POST /api/product-variant-values - Create variant value
   - [ ] PUT /api/product-variant-values/{id} - Update variant value
   - [ ] DELETE /api/product-variant-values/{id} - Delete variant value
```

#### Product Variant Price Controller
```php
// tests/Feature/Api/ProductVariantPriceControllerTest.php

1. **Variant Pricing**
   - [ ] GET /api/product-variant-prices - List variant prices
   - [ ] POST /api/product-variant-prices - Set variant price
   - [ ] PUT /api/product-variant-prices/{id} - Update price
   - [ ] DELETE /api/product-variant-prices/{id} - Delete price
```

### 4.3 Product Media Tests

#### Product Image Controller
```php
// tests/Feature/Api/ProductImageControllerTest.php

1. **Image Management**
   - [ ] GET /api/product-images - List product images
   - [ ] POST /api/product-images - Upload product image
   - [ ] PUT /api/product-images/{id} - Update image (set primary)
   - [ ] DELETE /api/product-images/{id} - Delete image
```

#### Product Shipping Info Controller
```php
// tests/Feature/Api/ProductShippingInfoControllerTest.php

1. **Shipping Information**
   - [ ] GET /api/product-shipping-info - List shipping info
   - [ ] POST /api/product-shipping-info - Add shipping info
   - [ ] PUT /api/product-shipping-info/{id} - Update shipping info
   - [ ] DELETE /api/product-shipping-info/{id} - Delete shipping info
```

### 4.4 Inventory Management Tests

#### Inventory Log Controller
```php
// tests/Feature/Api/InventoryLogControllerTest.php

1. **Inventory Tracking**
   - [ ] GET /api/inventory-logs - List inventory changes
   - [ ] POST /api/inventory-logs - Record inventory change
   - [ ] GET /api/inventory-logs/{id} - Get log details
   - [ ] GET /api/inventory-logs?product_id={id} - Logs by product
```

#### Variant Price Composition Controller
```php
// tests/Feature/Api/VariantPriceCompositionControllerTest.php

1. **Price Composition**
   - [ ] GET /api/variant-price-compositions - List compositions
   - [ ] POST /api/variant-price-compositions - Create composition
   - [ ] PUT /api/variant-price-compositions/{id} - Update composition
   - [ ] DELETE /api/variant-price-compositions/{id} - Delete composition
```

#### Variant Shipping Info Controller
```php
// tests/Feature/Api/VariantShippingInfoControllerTest.php

1. **Variant Shipping**
   - [ ] GET /api/variant-shipping-info - List shipping info
   - [ ] POST /api/variant-shipping-info - Add shipping info
   - [ ] PUT /api/variant-shipping-info/{id} - Update shipping info
   - [ ] DELETE /api/variant-shipping-info/{id} - Delete shipping info
```

## Phase 5: Shopping Cart & Order APIs Testing

### 5.1 Cart Controller Tests
**Controller:** `CartController`

#### Test Scenarios:
```php
// tests/Feature/Api/CartControllerTest.php

1. **Cart Management**
   - [ ] GET /api/carts - Get user cart
   - [ ] POST /api/carts - Create new cart
   - [ ] GET /api/carts/{id} - Get cart details
   - [ ] PUT /api/carts/{id} - Update cart
   - [ ] DELETE /api/carts/{id} - Clear cart

2. **Cart Operations**
   - [ ] POST /api/carts/{id}/merge - Merge carts
   - [ ] GET /api/carts/{id}/total - Calculate cart total
   - [ ] POST /api/carts/{id}/apply-coupon - Apply discount code
   - [ ] DELETE /api/carts/{id}/coupon - Remove discount
```

### 5.2 Cart Item Controller Tests
**Controller:** `CartItemController`

#### Test Scenarios:
```php
// tests/Feature/Api/CartItemControllerTest.php

1. **Cart Item Management**
   - [ ] GET /api/cart-items - List cart items
   - [ ] POST /api/cart-items - Add item to cart
   - [ ] GET /api/cart-items/{id} - Get item details
   - [ ] PUT /api/cart-items/{id} - Update item quantity
   - [ ] DELETE /api/cart-items/{id} - Remove item from cart

2. **Cart Item Operations**
   - [ ] POST /api/cart-items/{id}/increase - Increase quantity
   - [ ] POST /api/cart-items/{id}/decrease - Decrease quantity
   - [ ] POST /api/cart-items/bulk-add - Add multiple items
   - [ ] DELETE /api/cart-items/bulk-remove - Remove multiple items
```

### 5.3 Order Controller Tests
**Controller:** `OrderController`

#### Test Scenarios:
```php
// tests/Feature/Api/OrderControllerTest.php

1. **Order Management**
   - [ ] GET /api/orders - List user orders
   - [ ] POST /api/orders - Create new order
   - [ ] GET /api/orders/{id} - Get order details
   - [ ] PUT /api/orders/{id} - Update order
   - [ ] DELETE /api/orders/{id} - Cancel order

2. **Order Operations**
   - [ ] POST /api/orders/{id}/checkout - Process checkout
   - [ ] GET /api/orders/{id}/status - Get order status
   - [ ] PUT /api/orders/{id}/status - Update order status
   - [ ] GET /api/orders/{id}/tracking - Get tracking info
   - [ ] POST /api/orders/{id}/confirm-receipt - Confirm order receipt
```

### 5.4 Order Item Controller Tests
**Controller:** `OrderItemController`

#### Test Scenarios:
```php
// tests/Feature/Api/OrderItemControllerTest.php

1. **Order Item Management**
   - [ ] GET /api/order-items - List order items
   - [ ] GET /api/order-items/{id} - Get item details
   - [ ] PUT /api/order-items/{id} - Update item
   - [ ] DELETE /api/order-items/{id} - Remove item

2. **Order Item Operations**
   - [ ] GET /api/order-items?order_id={id} - Items by order
   - [ ] POST /api/order-items/{id}/review - Add review
   - [ ] POST /api/order-items/{id}/return - Request return
```

### 5.5 Order Status History Tests
**Controller:** `OrderStatusHistoryController`

#### Test Scenarios:
```php
// tests/Feature/Api/OrderStatusHistoryControllerTest.php

1. **Status History**
   - [ ] GET /api/order-status-history - List status changes
   - [ ] POST /api/order-status-history - Add status change
   - [ ] GET /api/order-status-history/{id} - Get status details
   - [ ] GET /api/order-status-history?order_id={id} - History by order
```

## Phase 6: Payment & Shipping APIs Testing

### 6.1 Payment Method Controller Tests
**Controller:** `PaymentMethodController`

#### Test Scenarios:
```php
// tests/Feature/Api/PaymentMethodControllerTest.php

1. **Payment Methods**
   - [ ] GET /api/payment-methods - List available methods
   - [ ] GET /api/payment-methods/{id} - Get method details
   - [ ] GET /api/payment-methods/active - List active methods
   - [ ] POST /api/payment-methods/{id}/validate - Validate payment method
```

### 6.2 Payment Transaction Controller Tests
**Controller:** `PaymentTransactionController`

#### Test Scenarios:
```php
// tests/Feature/Api/PaymentTransactionControllerTest.php

1. **Payment Transactions**
   - [ ] GET /api/payment-transactions - List transactions
   - [ ] POST /api/payment-transactions - Create transaction
   - [ ] GET /api/payment-transactions/{id} - Get transaction details
   - [ ] PUT /api/payment-transactions/{id} - Update transaction
   - [ ] POST /api/payment-transactions/{id}/confirm - Confirm payment
   - [ ] POST /api/payment-transactions/{id}/cancel - Cancel transaction
```

### 6.3 Payment Log Controller Tests
**Controller:** `PaymentLogController`

#### Test Scenarios:
```php
// tests/Feature/Api/PaymentLogControllerTest.php

1. **Payment Logs**
   - [ ] GET /api/payment-logs - List payment logs
   - [ ] POST /api/payment-logs - Create log entry
   - [ ] GET /api/payment-logs/{id} - Get log details
   - [ ] GET /api/payment-logs?transaction_id={id} - Logs by transaction
```

### 6.4 Shipping Method Controller Tests
**Controller:** `ShippingMethodController`

#### Test Scenarios:
```php
// tests/Feature/Api/ShippingMethodControllerTest.php

1. **Shipping Methods**
   - [ ] GET /api/shipping-methods - List shipping methods
   - [ ] GET /api/shipping-methods/{id} - Get method details
   - [ ] GET /api/shipping-methods/active - List active methods
   - [ ] POST /api/shipping-methods/{id}/calculate - Calculate shipping cost
```

### 6.5 Shipping Rate Controller Tests
**Controller:** `ShippingRateController`

#### Test Scenarios:
```php
// tests/Feature/Api/ShippingRateControllerTest.php

1. **Shipping Rates**
   - [ ] GET /api/shipping-rates - List shipping rates
   - [ ] POST /api/shipping-rates - Create shipping rate
   - [ ] GET /api/shipping-rates/{id} - Get rate details
   - [ ] PUT /api/shipping-rates/{id} - Update rate
   - [ ] DELETE /api/shipping-rates/{id} - Delete rate
   - [ ] GET /api/shipping-rates/calculate - Calculate shipping cost
```

### 6.6 Order Shipping Controller Tests
**Controller:** `OrderShippingController`

#### Test Scenarios:
```php
// tests/Feature/Api/OrderShippingControllerTest.php

1. **Order Shipping**
   - [ ] GET /api/order-shipping - List order shipping
   - [ ] POST /api/order-shipping - Create shipping record
   - [ ] GET /api/order-shipping/{id} - Get shipping details
   - [ ] PUT /api/order-shipping/{id} - Update shipping info
   - [ ] POST /api/order-shipping/{id}/track - Update tracking
```

## Phase 7: Review & Feedback APIs Testing

### 7.1 Product Review Controller Tests
**Controller:** `ProductReviewController`

#### Test Scenarios:
```php
// tests/Feature/Api/ProductReviewControllerTest.php

1. **Review Management**
   - [ ] GET /api/product-reviews - List reviews
   - [ ] POST /api/product-reviews - Create review
   - [ ] GET /api/product-reviews/{id} - Get review details
   - [ ] PUT /api/product-reviews/{id} - Update review
   - [ ] DELETE /api/product-reviews/{id} - Delete review

2. **Review Features**
   - [ ] GET /api/product-reviews?product_id={id} - Reviews by product
   - [ ] GET /api/product-reviews?user_id={id} - Reviews by user
   - [ ] POST /api/product-reviews/{id}/helpful - Mark as helpful
   - [ ] POST /api/product-reviews/{id}/report - Report review
```

### 7.2 Review Media Controller Tests
**Controller:** `ReviewMediaController`

#### Test Scenarios:
```php
// tests/Feature/Api/ReviewMediaControllerTest.php

1. **Review Media**
   - [ ] GET /api/review-media - List review media
   - [ ] POST /api/review-media - Upload review media
   - [ ] GET /api/review-media/{id} - Get media details
   - [ ] DELETE /api/review-media/{id} - Delete media
```

### 7.3 Review Vote Controller Tests
**Controller:** `ReviewVoteController`

#### Test Scenarios:
```php
// tests/Feature/Api/ReviewVoteControllerTest.php

1. **Review Votes**
   - [ ] GET /api/review-votes - List votes
   - [ ] POST /api/review-votes - Vote on review
   - [ ] DELETE /api/review-votes/{id} - Remove vote
   - [ ] GET /api/review-votes?review_id={id} - Votes by review
```

## Phase 8: Notification & Activity APIs Testing

### 8.1 User Notification Controller Tests
**Controller:** `UserNotificationController`

#### Test Scenarios:
```php
// tests/Feature/Api/UserNotificationControllerTest.php

1. **Notification Management**
   - [ ] GET /api/notifications - List user notifications
   - [ ] GET /api/notifications/{id} - Get notification details
   - [ ] PUT /api/notifications/{id}/read - Mark as read
   - [ ] PUT /api/notifications/read-all - Mark all as read
   - [ ] DELETE /api/notifications/{id} - Delete notification

2. **Notification Features**
   - [ ] GET /api/notifications/unread - Count unread notifications
   - [ ] POST /api/notifications/subscribe - Subscribe to notifications
   - [ ] POST /api/notifications/unsubscribe - Unsubscribe
```

### 8.2 User Activity Controller Tests
**Controller:** `UserActivityController`

#### Test Scenarios:
```php
// tests/Feature/Api/UserActivityControllerTest.php

1. **Activity Tracking**
   - [ ] GET /api/activities - List user activities
   - [ ] POST /api/activities - Log activity
   - [ ] GET /api/activities/{id} - Get activity details
   - [ ] GET /api/activities?user_id={id} - Activities by user
   - [ ] GET /api/activities?type={type} - Activities by type
```

### 8.3 Search History Controller Tests
**Controller:** `SearchHistoryController`

#### Test Scenarios:
```php
// tests/Feature/Api/SearchHistoryControllerTest.php

1. **Search History**
   - [ ] GET /api/search-history - List search history
   - [ ] POST /api/search-history - Record search
   - [ ] DELETE /api/search-history/{id} - Delete search record
   - [ ] DELETE /api/search-history - Clear all history
   - [ ] GET /api/search-history/trending - Get trending searches
```

## Phase 9: Admin & Reporting APIs Testing

### 9.1 Admin Controller Tests
**Controller:** `AdminController`

#### Test Scenarios:
```php
// tests/Feature/Api/AdminControllerTest.php

1. **Admin User Management**
   - [ ] GET /api/admin-users - List admin users
   - [ ] POST /api/admin-users - Create admin user
   - [ ] GET /api/admin-users/{id} - Get admin details
   - [ ] PUT /api/admin-users/{id} - Update admin user
   - [ ] DELETE /api/admin-users/{id} - Delete admin user

2. **Admin Operations**
   - [ ] GET /api/admin/dashboard - Get dashboard data
   - [ ] GET /api/admin/statistics - Get system statistics
   - [ ] POST /api/admin/users/{id}/suspend - Suspend user
   - [ ] POST /api/admin/users/{id}/activate - Activate user
```

### 9.2 Seller Report Controller Tests
**Controller:** `SellerReportController`

#### Test Scenarios:
```php
// tests/Feature/Api/SellerReportControllerTest.php

1. **Seller Reports**
   - [ ] GET /api/seller-reports - List seller reports
   - [ ] POST /api/seller-reports - Generate seller report
   - [ ] GET /api/seller-reports/{id} - Get report details
   - [ ] GET /api/seller-reports?seller_id={id} - Reports by seller
   - [ ] GET /api/seller-reports?period={period} - Reports by period
```

### 9.3 Sales Report Controller Tests
**Controller:** `SalesReportController`

#### Test Scenarios:
```php
// tests/Feature/Api/SalesReportControllerTest.php

1. **Sales Reports**
   - [ ] GET /api/sales-reports - List sales reports
   - [ ] POST /api/sales-reports - Generate sales report
   - [ ] GET /api/sales-reports/{id} - Get report details
   - [ ] GET /api/sales-reports?period={period} - Reports by period
   - [ ] GET /api/sales-reports/export - Export reports
```

## Phase 10: Chat System APIs Testing

### 10.1 Chat Conversation Controller Tests
**Controller:** `ChatConversationController`

#### Test Scenarios:
```php
// tests/Feature/Api/ChatConversationControllerTest.php

1. **Conversation Management**
   - [ ] GET /api/chat-conversations - List user conversations
   - [ ] POST /api/chat-conversations - Create new conversation
   - [ ] GET /api/chat-conversations/{id} - Get conversation details
   - [ ] PUT /api/chat-conversations/{id} - Update conversation
   - [ ] DELETE /api/chat-conversations/{id} - Delete conversation

2. **Conversation Features**
   - [ ] POST /api/chat-conversations/{id}/archive - Archive conversation
   - [ ] POST /api/chat-conversations/{id}/mute - Mute conversation
   - [ ] POST /api/chat-conversations/{id}/pin - Pin conversation
```

### 10.2 Chat Participant Controller Tests
**Controller:** `ChatParticipantController`

#### Test Scenarios:
```php
// tests/Feature/Api/ChatParticipantControllerTest.php

1. **Participant Management**
   - [ ] GET /api/chat-participants - List participants
   - [ ] POST /api/chat-participants - Add participant
   - [ ] GET /api/chat-participants/{id} - Get participant details
   - [ ] PUT /api/chat-participants/{id} - Update participant
   - [ ] DELETE /api/chat-participants/{id} - Remove participant

2. **Participant Features**
   - [ ] POST /api/chat-participants/{id}/admin - Make admin
   - [ ] POST /api/chat-participants/{id}/block - Block participant
   - [ ] POST /api/chat-participants/{id}/unblock - Unblock participant
```

### 10.3 Chat Message Controller Tests
**Controller:** `ChatMessageController`

#### Test Scenarios:
```php
// tests/Feature/Api/ChatMessageControllerTest.php

1. **Message Management**
   - [ ] GET /api/chat-messages - List messages
   - [ ] POST /api/chat-messages - Send message
   - [ ] GET /api/chat-messages/{id} - Get message details
   - [ ] PUT /api/chat-messages/{id} - Edit message
   - [ ] DELETE /api/chat-messages/{id} - Delete message

2. **Message Features**
   - [ ] GET /api/chat-messages?conversation_id={id} - Messages by conversation
   - [ ] POST /api/chat-messages/{id}/react - Add reaction
   - [ ] POST /api/chat-messages/{id}/reply - Reply to message
   - [ ] POST /api/chat-messages/{id}/forward - Forward message
```

### 10.4 Message Attachment Controller Tests
**Controller:** `MessageAttachmentController`

#### Test Scenarios:
```php
// tests/Feature/Api/MessageAttachmentControllerTest.php

1. **Attachment Management**
   - [ ] GET /api/message-attachments - List attachments
   - [ ] POST /api/message-attachments - Upload attachment
   - [ ] GET /api/message-attachments/{id} - Get attachment details
   - [ ] DELETE /api/message-attachments/{id} - Delete attachment
```

### 10.5 Message Status Controller Tests
**Controller:** `MessageStatusController`

#### Test Scenarios:
```php
// tests/Feature/Api/MessageStatusControllerTest.php

1. **Message Status**
   - [ ] GET /api/message-statuses - List message statuses
   - [ ] POST /api/message-statuses - Update message status
   - [ ] GET /api/message-statuses/{id} - Get status details
   - [ ] POST /api/message-statuses/mark-read - Mark messages as read
```

### 10.6 Message Reaction Controller Tests
**Controller:** `MessageReactionController`

#### Test Scenarios:
```php
// tests/Feature/Api/MessageReactionControllerTest.php

1. **Message Reactions**
   - [ ] GET /api/message-reactions - List reactions
   - [ ] POST /api/message-reactions - Add reaction
   - [ ] DELETE /api/message-reactions/{id} - Remove reaction
   - [ ] GET /api/message-reactions?message_id={id} - Reactions by message
```

### 10.7 Message Edit Controller Tests
**Controller:** `MessageEditController`

#### Test Scenarios:
```php
// tests/Feature/Api/MessageEditControllerTest.php

1. **Message Edit History**
   - [ ] GET /api/message-edits - List edit history
   - [ ] POST /api/message-edits - Record edit
   - [ ] GET /api/message-edits/{id} - Get edit details
   - [ ] GET /api/message-edits?message_id={id} - History by message
```

### 10.8 Chat Reference Controllers Tests

#### Chat Product Reference Controller
```php
// tests/Feature/Api/ChatProductReferenceControllerTest.php

1. **Product References**
   - [ ] GET /api/chat-product-references - List product references
   - [ ] POST /api/chat-product-references - Add product reference
   - [ ] GET /api/chat-product-references/{id} - Get reference details
```

#### Chat Order Reference Controller
```php
// tests/Feature/Api/ChatOrderReferenceControllerTest.php

1. **Order References**
   - [ ] GET /api/chat-order-references - List order references
   - [ ] POST /api/chat-order-references - Add order reference
   - [ ] GET /api/chat-order-references/{id} - Get reference details
```

### 10.9 Chat Report Controller Tests
**Controller:** `ChatReportController`

#### Test Scenarios:
```php
// tests/Feature/Api/ChatReportControllerTest.php

1. **Chat Reports**
   - [ ] GET /api/chat-reports - List chat reports
   - [ ] POST /api/chat-reports - Report conversation
   - [ ] GET /api/chat-reports/{id} - Get report details
   - [ ] PUT /api/chat-reports/{id} - Update report status
```

## Phase 11: System Settings APIs Testing

### 11.1 System Setting Controller Tests
**Controller:** `SystemSettingController`

#### Test Scenarios:
```php
// tests/Feature/Api/SystemSettingControllerTest.php

1. **System Settings**
   - [ ] GET /api/system-settings - List system settings
   - [ ] POST /api/system-settings - Create setting
   - [ ] GET /api/system-settings/{id} - Get setting details
   - [ ] PUT /api/system-settings/{id} - Update setting
   - [ ] DELETE /api/system-settings/{id} - Delete setting

2. **Setting Features**
   - [ ] GET /api/system-settings/public - Get public settings
   - [ ] POST /api/system-settings/bulk-update - Bulk update settings
   - [ ] GET /api/system-settings/export - Export settings
   - [ ] POST /api/system-settings/import - Import settings
```

## Testing Commands & Automation

### 1. Run All Tests
```bash
# Run entire test suite
php artisan test

# Run tests with coverage
php artisan test --coverage

# Run tests in parallel
php artisan test --parallel
```

### 2. Run Specific Test Categories
```bash
# Run authentication tests
php artisan test tests/Feature/Auth/

# Run API tests
php artisan test tests/Feature/Api/

# Run specific controller tests
php artisan test tests/Feature/Api/ProductControllerTest.php
```

### 3. Database Testing with Isolation
```bash
# Ensure testing database is completely isolated
php artisan migrate:fresh --env=testing --force

# Run tests with database transactions (rollback after each test)
php artisan test --testsuite=Feature --env=testing

# Seed test data for testing environment
php artisan db:seed --env=testing --class=TestDataSeeder --force

# Verify database isolation before running tests
php artisan tinker --env=testing
> DB::connection()->getDatabaseName();
# Should return: "zukses_test" NOT main database
```

### 4. Performance Testing
```bash
# Run performance tests
php artisan test --testsuite=Performance

# Generate performance reports
php artisan test --profile --coverage
```

### 5. API Documentation Testing
```bash
# Generate API documentation
php artisan api:generate --routePrefix=api

# Test API endpoints from documentation
php artisan test --testsuite=ApiDocumentation
```

## Testing Progress Tracking

### Checklist Template
```markdown
## API Testing Progress

### Phase 1: Authentication (6/6 completed)
- [x] AuthController tests

### Phase 2: Core User Management (3/3 completed)
- [x] UserController tests
- [x] SellerController tests
- [x] Device & Verification tests

### Phase 3: Address & Location (5/5 completed)
- [x] AddressController tests
- [x] Location controllers tests

### Phase 4: Product Management (10/10 completed)
- [x] ProductController tests
- [x] Product variant tests
- [x] Product media tests
- [x] Inventory tests

### Phase 5: Shopping & Orders (5/5 completed)
- [x] Cart & CartItem tests
- [x] Order management tests
- [x] Order status tests

### Phase 6: Payment & Shipping (6/6 completed)
- [x] Payment system tests
- [x] Shipping system tests

### Phase 7: Reviews & Feedback (3/3 completed)
- [x] Review system tests

### Phase 8: Notifications & Activities (3/3 completed)
- [x] Notification tests
- [x] Activity tracking tests

### Phase 9: Admin & Reports (3/3 completed)
- [x] Admin management tests
- [x] Report generation tests

### Phase 10: Chat System (10/10 completed)
- [x] Chat functionality tests

### Phase 11: System Settings (1/1 completed)
- [x] System settings tests

**Overall Progress: 52/52 controllers tested**
```

## Next Steps - Database Isolation Priority ⚠️

### **CRITICAL FIRST STEP: Database Setup**
1. **🔥 Create Dedicated Test Database** - MUST be separate from production database
2. **🔥 Verify Database Isolation** - Ensure tests never touch main database
3. **🔥 Configure .env.testing** - Complete testing environment setup
4. **Create Test Data Seeders** - Realistic test data for comprehensive testing
5. **Implement Tests** - Create test files for each controller
6. **Run Test Suite** - Execute comprehensive testing with database transactions
7. **Generate Reports** - Create test coverage and performance reports
8. **CI/CD Integration** - Integrate tests into deployment pipeline

### **Database Isolation Checklist:**
- [ ] Create `zukses_test` database with separate user credentials
- [ ] Configure `.env.testing` with test database settings
- [ ] Create/update `phpunit.xml` with test database configuration
- [ ] Verify test database connection returns `zukses_test`
- [ ] Confirm main database has NO test data
- [ ] Run `php artisan migrate:fresh --env=testing` successfully
- [ ] Seed test data in test database only
- [ ] Test isolation verification commands pass

**Total Test Scenarios:** 300+ individual test cases
**Estimated Testing Time:** 2-3 weeks for complete implementation
**Priority:** Critical for production deployment

This comprehensive testing plan ensures all API endpoints are thoroughly tested with various scenarios including success cases, error handling, validation, and edge cases.