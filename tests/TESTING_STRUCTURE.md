# Testing Structure Documentation

## 📁 Struktur Folder Testing

```
tests/
├── Feature/                          # Feature Tests (API Endpoints)
│   ├── Api/
│   │   ├── Auth/                    # Authentication Endpoints (9 endpoints)
│   │   │   ├── AuthControllerTest.php
│   │   │   ├── AuthValidationTest.php
│   │   │   └── AuthSecurityTest.php
│   │   ├── Users/                   # User Management (8 endpoints)
│   │   │   ├── UserControllerTest.php
│   │   │   ├── ProfileControllerTest.php
│   │   │   └── SellerControllerTest.php
│   │   ├── Products/                # Product Management (10 endpoints)
│   │   │   ├── ProductControllerTest.php
│   │   │   ├── CategoryControllerTest.php
│   │   │   ├── ProductVariantTest.php
│   │   │   └── ProductImageTest.php
│   │   ├── Carts/                   # Shopping Cart (2 endpoints)
│   │   │   └── CartControllerTest.php
│   │   ├── Orders/                  # Order Management (3 endpoints)
│   │   │   ├── OrderControllerTest.php
│   │   │   └── OrderStatusTest.php
│   │   ├── Payments/                # Payment & Shipping (8 endpoints)
│   │   │   ├── PaymentControllerTest.php
│   │   │   └── ShippingControllerTest.php
│   │   ├── Addresses/               # Address & Location (5 endpoints)
│   │   │   ├── AddressControllerTest.php
│   │   │   └── LocationControllerTest.php
│   │   ├── Chat/                    # Chat System (10 endpoints)
│   │   │   ├── ChatConversationTest.php
│   │   │   ├── ChatMessageTest.php
│   │   │   └── ChatAttachmentTest.php
│   │   ├── Notifications/           # Notifications (3 endpoints)
│   │   │   └── NotificationControllerTest.php
│   │   ├── Admin/                   # Admin & Reporting (3 endpoints)
│   │   │   └── AdminControllerTest.php
│   │   ├── Reviews/                 # Review & Feedback (3 endpoints)
│   │   │   └── ReviewControllerTest.php
│   │   ├── Devices/                 # Device Management (6 endpoints)
│   │   │   └── DeviceControllerTest.php
│   │   └── Integration/             # Cross-feature Integration Tests
│   │       ├── UserJourneyTest.php
│   │       ├── OrderFlowTest.php
│   │       └── PaymentIntegrationTest.php
├── Unit/                            # Unit Tests
│   ├── Models/                      # Model Tests
│   │   ├── UserTest.php
│   │   ├── ProductTest.php
│   │   └── OrderTest.php
│   ├── Services/                    # Service Tests
│   │   ├── AuthServiceTest.php
│   │   ├── ProductServiceTest.php
│   │   └── PaymentServiceTest.php
│   ├── Repositories/                # Repository Tests
│   │   ├── UserRepositoryTest.php
│   │   └── ProductRepositoryTest.php
│   ├── Middleware/                  # Middleware Tests
│   │   └── JwtMiddlewareTest.php
│   └── Helpers/                     # Helper Tests
│       └── ValidationHelperTest.php
├── Support/                         # Testing Support Files
│   ├── Traits/                      # Reusable Test Traits
│   │   ├── JwtAuthenticationTrait.php
│   │   ├── DatabaseSetupTrait.php
│   │   └── ApiTestTrait.php
│   ├── Factories/                   # Custom Factories
│   │   ├── UserFactory.php
│   │   └── ProductFactory.php
│   ├── Helpers/                     # Test Helper Functions
│   │   └── TestHelper.php
│   └── Data/                        # Test Data Fixtures
│       ├── users.json
│       └── products.json
├── TestCase.php                     # Base Test Class
└── bootstrap.php                    # Test Bootstrap Configuration
```

## 📊 Endpoint Coverage Plan

### Total Endpoints: 82
- **Public**: 7 endpoints
- **JWT Protected**: 75 endpoints

### Prioritas Testing:
1. **High Priority** (Authentication & Core Features)
   - Auth endpoints (9)
   - User management (8)
   - Product management (10)

2. **Medium Priority** (Business Logic)
   - Order processing (3)
   - Shopping cart (2)
   - Payment & shipping (8)

3. **Standard Priority** (Supporting Features)
   - Address & location (5)
   - Chat system (10)
   - Notifications (3)
   - Reviews (3)
   - Device management (6)
   - Admin & reporting (3)
   - System settings (1)

## 🎯 Testing Goals

### Coverage Target:
- **Unit Tests**: 90%+ code coverage
- **Feature Tests**: 100% endpoint coverage
- **Integration Tests**: Critical user journeys

### Test Types:
1. **Happy Path Tests** - Normal expected behavior
2. **Validation Tests** - Input validation & error handling
3. **Security Tests** - JWT validation, authorization
4. **Edge Case Tests** - Boundary conditions
5. **Performance Tests** - Load testing (optional)

## 🚀 Test Execution Commands

```bash
# Run all tests
./vendor/bin/phpunit

# Run specific test category
./vendor/bin/phpunit tests/Feature/Api/Auth
./vendor/bin/phpunit tests/Unit/Models

# Run with coverage
./vendor/bin/phpunit --coverage-html coverage

# Run specific test file
./vendor/bin/phpunit tests/Feature/Api/Auth/AuthControllerTest.php

# Run tests with specific group
./vendor/bin/phpunit --group auth
./vendor/bin/phpunit --group integration
```

## 📝 Test Naming Conventions

### File Naming:
- Feature Tests: `*ControllerTest.php`, `*FeatureTest.php`
- Unit Tests: `*Test.php`
- Integration Tests: `*IntegrationTest.php`

### Method Naming:
- `test_*` - Standard test methods
- `*_happy_path()` - Success scenario tests
- `*_validation_error()` - Validation tests
- `*_security()` - Security tests
- `*_edge_case()` - Edge case tests

### Example:
```php
public function test_user_registration_happy_path()
public function test_user_registration_validation_error()
public function test_user_registration_security()
```