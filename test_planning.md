# Unit Testing Plan for API Controllers

## Overview
This document outlines the comprehensive unit testing plan for all API controllers in the Zukses E-commerce application. Each controller requires thorough testing for all endpoints including authentication, validation, business logic, error handling, and edge cases.

## Testing Framework
- Laravel provides built-in testing support using PHPUnit
- Use Laravel's testing helpers like `$this->get()`, `$this->post()`, `$this->put()`, `$this->delete()`
- Use `Sanctum::actingAs()` for authentication testing
- Use `RefreshDatabase` trait for database testing

## General Testing Principles
- Test all HTTP methods in each controller
- Test authentication and authorization for protected endpoints
- Test validation rules for all input data
- Test success and failure scenarios
- Test with proper data factories
- Test error responses and exception handling
- Test rate limiting if applicable

## Controller-Specific Test Plans

### 1. AddressController
**Methods to test:**
- `index()` - Get all addresses
- `store()` - Create new address
- `show($address)` - Get specific address
- `update($address)` - Update specific address
- `destroy($address)` - Delete specific address

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test that users can only access their own addresses
- [ ] Validation: Test all validation rules for address creation/update
- [ ] Success: Test successful address creation with valid data
- [ ] Success: Test successful address retrieval
- [ ] Success: Test successful address update
- [ ] Success: Test successful address deletion
- [ ] Error: Test with unauthenticated user
- [ ] Error: Test accessing another user's addresses
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent address

**Detailed implementation checklist:**
- [ ] Create `AddressControllerTest` class in `tests/Feature/Api/`
- [ ] Import necessary classes (`TestCase`, `RefreshDatabase`, `WithFaker`, `User`, `Address`, `Sanctum`)
- [ ] Implement `RefreshDatabase` and `WithFaker` traits
- [ ] Create setUp method to initialize common objects
- [ ] Create helper method for authenticated user
- [ ] Create Address factory for generating test data
- [ ] Create User factory for authentication
- [ ] Test unauthenticated access to index endpoint
  - [ ] Make GET request to `/api/addresses` without token
  - [ ] Assert 401 Unauthorized response
  - [ ] Assert error message structure
- [ ] Test authenticated access to index endpoint
  - [ ] Create authenticated user
  - [ ] Create multiple addresses for the user
  - [ ] Make GET request to `/api/addresses` with valid token
  - [ ] Assert 200 OK response
  - [ ] Assert response is JSON
  - [ ] Assert correct data structure in response
  - [ ] Assert user can only see their own addresses
  - [ ] Test pagination if implemented
- [ ] Test unauthenticated access to store endpoint
  - [ ] Make POST request to `/api/addresses` without token
  - [ ] Assert 401 Unauthorized response
- [ ] Test authenticated access to store endpoint
  - [ ] Prepare valid address data using faker
  - [ ] Make POST request to `/api/addresses` with valid token
  - [ ] Assert 201 Created response
  - [ ] Assert address is created in database
  - [ ] Assert response contains correct address data
- [ ] Test validation for store endpoint
  - [ ] Test with missing required field (e.g. no nama_penerima)
  - [ ] Test with invalid email format
  - [ ] Test with string in numeric field
  - [ ] Test with data exceeding max length
  - [ ] Assert 422 Unprocessable Entity for all validation failures
  - [ ] Assert proper error message structure
- [ ] Test unauthenticated access to show endpoint
  - [ ] Make GET request to `/api/addresses/{id}` without token
  - [ ] Assert 401 Unauthorized response
- [ ] Test authenticated access to show endpoint
  - [ ] Create address for user
  - [ ] Make GET request to `/api/addresses/{id}` with valid token
  - [ ] Assert 200 OK response
  - [ ] Assert response contains correct address data
- [ ] Test unauthorized access to another user's address
  - [ ] Create address for user A
  - [ ] Authenticate as user B
  - [ ] Try to access user A's address
  - [ ] Assert 404 Not Found or appropriate error
- [ ] Test unauthenticated access to update endpoint
  - [ ] Make PUT request to `/api/addresses/{id}` without token
  - [ ] Assert 401 Unauthorized response
- [ ] Test authenticated access to update endpoint
  - [ ] Create address for user
  - [ ] Prepare valid update data
  - [ ] Make PUT request to `/api/addresses/{id}` with valid token
  - [ ] Assert 200 OK response
  - [ ] Assert address is updated in database
- [ ] Test validation for update endpoint
  - [ ] Test with invalid data
  - [ ] Test with required field as empty string
  - [ ] Assert 422 Unprocessable Entity for validation failures
- [ ] Test unauthenticated access to destroy endpoint
  - [ ] Make DELETE request to `/api/addresses/{id}` without token
  - [ ] Assert 401 Unauthorized response
- [ ] Test authenticated access to destroy endpoint
  - [ ] Create address for user
  - [ ] Make DELETE request to `/api/addresses/{id}` with valid token
  - [ ] Assert 200 OK response
  - [ ] Assert address is deleted from database
- [ ] Test unauthorized deletion of another user's address
  - [ ] Create address for user A
  - [ ] Authenticate as user B
  - [ ] Try to delete user A's address
  - [ ] Assert appropriate error response
- [ ] Test error handling for non-existent addresses
  - [ ] Try to access/update/delete non-existent address
  - [ ] Assert 404 Not Found response
- [ ] Test business logic validations
  - [ ] Test address limit per user if applicable
  - [ ] Test default address handling
- [ ] Test response data structure consistency
  - [ ] Verify all responses follow the same structure
  - [ ] Verify required fields are always present
- [ ] Create test coverage report for AddressController

### 2. AdminController
**Methods to test:**
- `index()` - Get all admins
- `store()` - Create new admin
- `show($user)` - Get specific admin
- `update($user)` - Update specific admin
- `destroy($user)` - Delete specific admin

**Test scenarios:**
- [ ] Authentication: Test with authenticated admin user
- [ ] Authorization: Test with super admin permissions only
- [ ] Validation: Test all validation rules for admin creation/update
- [ ] Success: Test successful admin creation with valid data
- [ ] Success: Test successful admin retrieval
- [ ] Success: Test successful admin update
- [ ] Success: Test successful admin deletion
- [ ] Error: Test with regular user (should fail)
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent admin

**Detailed implementation checklist:**
- [ ] Create `AdminControllerTest` class in `tests/Feature/Api/`
- [ ] Import necessary classes (`TestCase`, `RefreshDatabase`, `WithFaker`, `User`, `Sanctum`)
- [ ] Implement `RefreshDatabase` and `WithFaker` traits
- [ ] Create setUp method to initialize common objects
- [ ] Create helper methods for different user roles (regular user, admin, super admin)
- [ ] Create User factory with different user types
- [ ] Test unauthenticated access to index endpoint
  - [ ] Make GET request to `/api/admins` without token
  - [ ] Assert 401 Unauthorized response
  - [ ] Assert proper error message format
- [ ] Test regular user access to index endpoint
  - [ ] Authenticate as regular user
  - [ ] Make GET request to `/api/admins`
  - [ ] Assert 403 Forbidden response
- [ ] Test admin user access to index endpoint
  - [ ] Authenticate as admin user
  - [ ] Make GET request to `/api/admins`
  - [ ] Assert 200 OK response
  - [ ] Assert response contains admin user list
  - [ ] Test pagination if implemented
- [ ] Test super admin access to index endpoint
  - [ ] Authenticate as super admin
  - [ ] Make GET request to `/api/admins`
  - [ ] Assert 200 OK response
  - [ ] Assert response contains all admin users
- [ ] Test unauthenticated access to store endpoint
  - [ ] Make POST request to `/api/admins` without token
  - [ ] Assert 401 Unauthorized response
- [ ] Test regular user access to store endpoint
  - [ ] Authenticate as regular user
  - [ ] Make POST request to `/api/admins` with valid admin data
  - [ ] Assert 403 Forbidden response
- [ ] Test admin access to store endpoint
  - [ ] Authenticate as regular admin
  - [ ] Make POST request to `/api/admins` with valid admin data
  - [ ] Assert 403 Forbidden response (if only super admin can create)
- [ ] Test super admin access to store endpoint
  - [ ] Authenticate as super admin
  - [ ] Prepare valid admin creation data
  - [ ] Make POST request to `/api/admins`
  - [ ] Assert 201 Created response
  - [ ] Assert new admin is created in database
  - [ ] Assert response contains correct admin data
- [ ] Test validation for store endpoint
  - [ ] Test with missing required fields
  - [ ] Test with invalid email format
  - [ ] Test with duplicate email/username
  - [ ] Test with invalid user type
  - [ ] Assert 422 Unprocessable Entity for all validation failures
  - [ ] Assert proper validation error format
- [ ] Test unauthenticated access to show endpoint
  - [ ] Make GET request to `/api/admins/{id}` without token
  - [ ] Assert 401 Unauthorized response
- [ ] Test regular user access to show endpoint
  - [ ] Authenticate as regular user
  - [ ] Try to access any admin profile
  - [ ] Assert 403 Forbidden response
- [ ] Test admin access to show endpoint
  - [ ] Authenticate as admin
  - [ ] Make GET request to `/api/admins/{id}` for own profile
  - [ ] Assert 200 OK response
  - [ ] Make GET request to `/api/admins/{id}` for other admin
  - [ ] Assert appropriate access control
- [ ] Test unauthenticated access to update endpoint
  - [ ] Make PUT request to `/api/admins/{id}` without token
  - [ ] Assert 401 Unauthorized response
- [ ] Test regular user access to update endpoint
  - [ ] Authenticate as regular user
  - [ ] Make PUT request to `/api/admins/{id}`
  - [ ] Assert 403 Forbidden response
- [ ] Test admin access to update endpoint
  - [ ] Authenticate as regular admin
  - [ ] Update own profile
  - [ ] Assert 200 OK response
  - [ ] Update other admin profile
  - [ ] Assert 403 Forbidden or appropriate access control
- [ ] Test super admin access to update endpoint
  - [ ] Authenticate as super admin
  - [ ] Make PUT request to `/api/admins/{id}` for any admin
  - [ ] Assert 200 OK response
  - [ ] Assert admin is updated in database
- [ ] Test validation for update endpoint
  - [ ] Test with invalid email format
  - [ ] Test with duplicate email
  - [ ] Test with invalid status values
  - [ ] Assert 422 Unprocessable Entity for validation errors
- [ ] Test unauthenticated access to destroy endpoint
  - [ ] Make DELETE request to `/api/admins/{id}` without token
  - [ ] Assert 401 Unauthorized response
- [ ] Test regular user access to destroy endpoint
  - [ ] Authenticate as regular user
  - [ ] Make DELETE request to `/api/admins/{id}`
  - [ ] Assert 403 Forbidden response
- [ ] Test admin access to destroy endpoint
  - [ ] Authenticate as regular admin
  - [ ] Try to delete own account
  - [ ] Assert appropriate response
  - [ ] Try to delete other admin account
  - [ ] Assert 403 Forbidden or appropriate access control
- [ ] Test super admin access to destroy endpoint
  - [ ] Authenticate as super admin
  - [ ] Make DELETE request to `/api/admins/{id}`
  - [ ] Assert 200 OK response
  - [ ] Assert admin is deleted from database
- [ ] Test access to non-existent admin
  - [ ] Try to access/update/delete non-existent admin ID
  - [ ] Assert 404 Not Found response
- [ ] Test business logic validations
  - [ ] Test prevention of deleting last super admin
  - [ ] Test permission inheritance
- [ ] Test response data structure consistency
  - [ ] Verify admin data doesn't expose sensitive information
  - [ ] Verify appropriate fields are returned based on requester's role
- [ ] Create test coverage report for AdminController

### 3. AuthController
**Methods to test:**
- `register()` - User registration
- `login()` - User login
- `sendOtp()` - Send OTP
- `logout()` - User logout
- `me()` - Get current user
- `forgotPassword()` - Forgot password
- `verifyOtp()` - Verify OTP
- `resetPassword()` - Reset password
- `redirectToGoogle()` - Google auth redirect
- `handleGoogleCallback()` - Google auth callback

**Test scenarios:**
- [ ] Register: Test successful registration with valid data
- [ ] Register: Test registration with invalid data
- [ ] Register: Test registration with duplicate email/phone
- [ ] Login: Test successful login with valid credentials
- [ ] Login: Test login with invalid credentials
- [ ] Login: Test login with non-existent user
- [ ] Login: Test device information storage
- [ ] OTP: Test successful OTP sending
- [ ] OTP: Test OTP sending with invalid contact
- [ ] OTP: Test OTP verification with valid code
- [ ] OTP: Test OTP verification with invalid code
- [ ] OTP: Test OTP verification with expired code
- [ ] Logout: Test successful logout
- [ ] Logout: Test logout without authentication
- [ ] Me: Test getting current user info
- [ ] Me: Test without authentication (should fail)
- [ ] Forgot Password: Test successful OTP sending for password reset
- [ ] Forgot Password: Test with non-existent user
- [ ] Reset Password: Test successful password reset
- [ ] Reset Password: Test with invalid data
- [ ] Google Auth: Test Google authentication callback

### 4. CartController
**Methods to test:**
- `index()` - Get user's cart
- `store()` - Add item to cart
- `show($cart)` - Get specific cart
- `update($cart)` - Update cart
- `destroy($cart)` - Remove cart

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test that users can only access their own carts
- [ ] Validation: Test all validation rules for cart operations
- [ ] Success: Test successful cart retrieval
- [ ] Success: Test successful addition to cart
- [ ] Success: Test successful cart update
- [ ] Success: Test successful cart removal
- [ ] Error: Test with unauthenticated user
- [ ] Error: Test accessing another user's cart
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent cart
- [ ] Business Logic: Test inventory availability
- [ ] Business Logic: Test quantity limits

### 5. CartItemController
**Methods to test:**
- `index()` - Get cart items
- `store()` - Add item to cart
- `show($cartItem)` - Get specific cart item
- `update($cartItem)` - Update cart item
- `destroy($cartItem)` - Remove cart item

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test that users can only access their own cart items
- [ ] Validation: Test all validation rules for cart item operations
- [ ] Success: Test successful cart items retrieval
- [ ] Success: Test successful addition of cart item
- [ ] Success: Test successful cart item update (quantity)
- [ ] Success: Test successful cart item removal
- [ ] Error: Test with unauthenticated user
- [ ] Error: Test accessing another user's cart items
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent cart item
- [ ] Business Logic: Test quantity constraints
- [ ] Business Logic: Test inventory availability

### 6. CategoryProductController
**Methods to test:**
- `index()` - Get all categories
- `store()` - Create new category
- `show($category)` - Get specific category
- `update($category)` - Update category
- `destroy($category)` - Delete category

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules for category operations
- [ ] Success: Test successful category retrieval
- [ ] Success: Test successful category creation
- [ ] Success: Test successful category update
- [ ] Success: Test successful category deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent category
- [ ] Relations: Test category-product relationships

### 7. ChatConversationController
**Methods to test:**
- `index()` - Get all conversations
- `store()` - Create new conversation
- `show($conversation)` - Get specific conversation
- `update($conversation)` - Update conversation
- `destroy($conversation)` - Delete conversation

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test that users can only access their conversations
- [ ] Validation: Test all validation rules for conversations
- [ ] Success: Test successful conversation retrieval
- [ ] Success: Test successful conversation creation
- [ ] Success: Test successful conversation update
- [ ] Success: Test successful conversation deletion
- [ ] Error: Test with unauthenticated user
- [ ] Error: Test accessing another user's conversations
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent conversation
- [ ] Business Logic: Test conversation access permissions

### 8. ChatMessageController
**Methods to test:**
- `index()` - Get messages in conversation
- `store()` - Send message
- `show($message)` - Get specific message
- `update($message)` - Update message
- `destroy($message)` - Delete message

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test that users can only access allowed messages
- [ ] Validation: Test all validation rules for messages
- [ ] Success: Test successful message retrieval
- [ ] Success: Test successful message sending
- [ ] Success: Test successful message update
- [ ] Success: Test successful message deletion
- [ ] Error: Test with unauthenticated user
- [ ] Error: Test accessing another user's messages
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent message
- [ ] Business Logic: Test message permissions based on conversation participants

### 9. ChatOrderReferenceController
**Methods to test:**
- `index()` - Get order references
- `store()` - Create order reference
- `show($reference)` - Get specific reference
- `update($reference)` - Update reference
- `destroy($reference)` - Delete reference

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful creation
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent reference

### 10. ChatParticipantController
**Methods to test:**
- `index()` - Get participants
- `store()` - Add participant
- `show($participant)` - Get specific participant
- `update($participant)` - Update participant
- `destroy($participant)` - Remove participant

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful addition
- [ ] Success: Test successful update
- [ ] Success: Test successful removal
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent participant

### 11. ChatProductReferenceController
**Methods to test:**
- `index()` - Get product references
- `store()` - Add product reference
- `show($reference)` - Get specific reference
- `update($reference)` - Update reference
- `destroy($reference)` - Delete reference

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful addition
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent reference

### 12. ChatReportController
**Methods to test:**
- `index()` - Get reports
- `store()` - Create report
- `show($report)` - Get specific report
- `update($report)` - Update report
- `destroy($report)` - Delete report

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful creation
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent report

### 13. CityController
**Methods to test:**
- `index()` - Get all cities
- `store()` - Create new city
- `show($city)` - Get specific city
- `update($city)` - Update city
- `destroy($city)` - Delete city

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful creation
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent city
- [ ] Relations: Test with province relationships

### 14. DeviceController
**Methods to test:**
- `index()` - Get all devices
- `store()` - Register device
- `show($device)` - Get specific device
- `update($device)` - Update device
- `destroy($device)` - Delete device

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test that users can only access their own devices
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful registration
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with unauthenticated user
- [ ] Error: Test accessing another user's devices
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent device

### 15. DistrictController
**Methods to test:**
- `index()` - Get all districts
- `store()` - Create new district
- `show($district)` - Get specific district
- `update($district)` - Update district
- `destroy($district)` - Delete district

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful creation
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent district
- [ ] Relations: Test with city relationships

### 16. InventoryLogController
**Methods to test:**
- `index()` - Get all inventory logs
- `store()` - Create new log
- `show($log)` - Get specific log
- `update($log)` - Update log
- `destroy($log)` - Delete log

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful creation
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent log
- [ ] Business Logic: Test inventory tracking

### 17. MessageAttachmentController
**Methods to test:**
- `store()` - Upload attachment
- `show($attachment)` - Get specific attachment
- `destroy($attachment)` - Delete attachment

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] File Validation: Test file type, size, and format validation
- [ ] Success: Test successful file upload
- [ ] Success: Test successful attachment retrieval
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid file types
- [ ] Error: Test with oversized files
- [ ] Error: Test with non-existent attachment

### 18. MessageEditController
**Methods to test:**
- `index()` - Get all message edits
- `store()` - Create message edit
- `show($edit)` - Get specific edit
- `update($edit)` - Update edit
- `destroy($edit)` - Delete edit

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test that users can only edit their own messages
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful creation
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent edit
- [ ] Business Logic: Test edit time constraints

### 19. MessageReactionController
**Methods to test:**
- `index()` - Get reactions
- `store()` - Add reaction
- `show($reaction)` - Get specific reaction
- `destroy($reaction)` - Remove reaction

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful addition
- [ ] Success: Test successful removal
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent reaction
- [ ] Business Logic: Test reaction limits per message

### 20. MessageStatusController
**Methods to test:**
- `index()` - Get message status
- `store()` - Update message status
- `show($status)` - Get specific status
- `update($status)` - Update status
- `destroy($status)` - Delete status

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent status

### 21. OrderController
**Methods to test:**
- `index()` - Get all orders
- `store()` - Create new order
- `show($order)` - Get specific order
- `update($order)` - Update order
- `destroy($order)` - Cancel order

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test that users can only access their own orders
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful order retrieval
- [ ] Success: Test successful order creation
- [ ] Success: Test successful order update
- [ ] Success: Test successful order cancellation
- [ ] Error: Test with unauthenticated user
- [ ] Error: Test accessing another user's orders
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent order
- [ ] Business Logic: Test inventory stock during order creation
- [ ] Business Logic: Test order status transitions
- [ ] Business Logic: Test payment validation

### 22. OrderItemController
**Methods to test:**
- `index()` - Get order items
- `store()` - Add item to order
- `show($item)` - Get specific item
- `update($item)` - Update item
- `destroy($item)` - Remove item

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful addition
- [ ] Success: Test successful update
- [ ] Success: Test successful removal
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent item

### 23. OrderShippingController
**Methods to test:**
- `index()` - Get shipping info
- `store()` - Add shipping info
- `show($shipping)` - Get specific shipping
- `update($shipping)` - Update shipping
- `destroy($shipping)` - Delete shipping

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful addition
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent shipping
- [ ] Business Logic: Test shipping cost calculation

### 24. OrderStatusHistoryController
**Methods to test:**
- `index()` - Get status history
- `store()` - Add status history
- `show($history)` - Get specific history
- `update($history)` - Update history
- `destroy($history)` - Delete history

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful addition
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent history
- [ ] Business Logic: Test status transition rules

### 25. PaymentLogController
**Methods to test:**
- `index()` - Get payment logs
- `store()` - Create payment log
- `show($log)` - Get specific log
- `update($log)` - Update log
- `destroy($log)` - Delete log

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful creation
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent log
- [ ] Business Logic: Test payment status tracking

### 26. PaymentMethodController
**Methods to test:**
- `index()` - Get payment methods
- `store()` - Add payment method
- `show($method)` - Get specific method
- `update($method)` - Update method
- `destroy($method)` - Delete method

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful addition
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent method

### 27. PaymentTransactionController
**Methods to test:**
- `index()` - Get transactions
- `store()` - Create transaction
- `show($transaction)` - Get specific transaction
- `update($transaction)` - Update transaction
- `destroy($transaction)` - Delete transaction

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful creation
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent transaction
- [ ] Business Logic: Test transaction validation
- [ ] Business Logic: Test payment processing

### 28. PostalCodeController
**Methods to test:**
- `index()` - Get postal codes
- `store()` - Create postal code
- `show($code)` - Get specific code
- `update($code)` - Update code
- `destroy($code)` - Delete code

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful creation
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent code
- [ ] Relations: Test with district relationships

### 29. ProductController
**Methods to test:**
- `index()` - Get all products
- `store()` - Create new product
- `show($product)` - Get specific product
- `update($product)` - Update product
- `destroy($product)` - Delete product

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions (seller/admin)
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful product retrieval
- [ ] Success: Test successful product creation
- [ ] Success: Test successful product update
- [ ] Success: Test successful product deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent product
- [ ] Business Logic: Test inventory management
- [ ] Business Logic: Test product approval workflow

### 30. ProductImageController
**Methods to test:**
- `index()` - Get product images
- `store()` - Upload image
- `show($image)` - Get specific image
- `update($image)` - Update image
- `destroy($image)` - Delete image

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] File Validation: Test file type, size, and format validation
- [ ] Success: Test successful image retrieval
- [ ] Success: Test successful image upload
- [ ] Success: Test successful image update
- [ ] Success: Test successful image deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid file types
- [ ] Error: Test with oversized files
- [ ] Error: Test with non-existent image

### 31. ProductReviewController
**Methods to test:**
- `index()` - Get reviews
- `store()` - Create review
- `show($review)` - Get specific review
- `update($review)` - Update review
- `destroy($review)` - Delete review

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful creation
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent review
- [ ] Business Logic: Test review permissions (only for purchased products)

### 32. ProductShippingInfoController
**Methods to test:**
- `index()` - Get shipping info
- `store()` - Add shipping info
- `show($info)` - Get specific info
- `update($info)` - Update info
- `destroy($info)` - Delete info

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful addition
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent info

### 33. ProductVariantController
**Methods to test:**
- `index()` - Get variants
- `store()` - Create variant
- `show($variant)` - Get specific variant
- `update($variant)` - Update variant
- `destroy($variant)` - Delete variant

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful creation
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent variant
- [ ] Business Logic: Test variant availability

### 34. ProductVariantPriceController
**Methods to test:**
- `index()` - Get variant prices
- `store()` - Create variant price
- `show($price)` - Get specific price
- `update($price)` - Update price
- `destroy($price)` - Delete price

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful creation
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent price
- [ ] Business Logic: Test price validation

### 35. ProductVariantValueController
**Methods to test:**
- `index()` - Get variant values
- `store()` - Create variant value
- `show($value)` - Get specific value
- `update($value)` - Update value
- `destroy($value)` - Delete value

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful creation
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent value

### 36. ProvinceController
**Methods to test:**
- `index()` - Get all provinces
- `store()` - Create new province
- `show($province)` - Get specific province
- `update($province)` - Update province
- `destroy($province)` - Delete province

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful creation
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent province

### 37. ReviewMediaController
**Methods to test:**
- `index()` - Get review media
- `store()` - Upload media
- `show($media)` - Get specific media
- `destroy($media)` - Delete media

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] File Validation: Test file type, size, and format validation
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful upload
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid file types
- [ ] Error: Test with oversized files
- [ ] Error: Test with non-existent media

### 38. ReviewVoteController
**Methods to test:**
- `store()` - Vote on review
- `destroy($vote)` - Remove vote

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful vote addition
- [ ] Success: Test successful vote removal
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent vote
- [ ] Business Logic: Test vote limits per user

### 39. SalesReportController
**Methods to test:**
- `index()` - Get sales reports
- `show($report)` - Get specific report

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions (admin/seller)
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful report retrieval
- [ ] Success: Test successful specific report retrieval
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent report
- [ ] Business Logic: Test report filtering and aggregation

### 40. SearchHistoryController
**Methods to test:**
- `index()` - Get search history
- `store()` - Add search
- `destroy($history)` - Clear search

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test that users can only access their own history
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful addition
- [ ] Success: Test successful deletion
- [ ] Error: Test with unauthenticated user
- [ ] Error: Test accessing another user's history
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent history

### 41. SellerController
**Methods to test:**
- `index()` - Get sellers
- `store()` - Create seller
- `show($seller)` - Get specific seller
- `update($seller)` - Update seller
- `destroy($seller)` - Delete seller

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful creation
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent seller

### 42. SellerReportController
**Methods to test:**
- `index()` - Get seller reports
- `show($report)` - Get specific report

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions (admin/seller)
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful report retrieval
- [ ] Success: Test successful specific report retrieval
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent report
- [ ] Business Logic: Test report filtering and aggregation

### 43. SessionController
**Methods to test:**
- `index()` - Get user sessions
- `destroy($session)` - Revoke session

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test that users can only access their own sessions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful session retrieval
- [ ] Success: Test successful session revocation
- [ ] Error: Test with unauthenticated user
- [ ] Error: Test accessing another user's sessions
- [ ] Error: Test with non-existent session

### 44. ShippingMethodController
**Methods to test:**
- `index()` - Get shipping methods
- `store()` - Create method
- `show($method)` - Get specific method
- `update($method)` - Update method
- `destroy($method)` - Delete method

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful creation
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent method

### 45. ShippingRateController
**Methods to test:**
- `index()` - Get shipping rates
- `store()` - Create rate
- `show($rate)` - Get specific rate
- `update($rate)` - Update rate
- `destroy($rate)` - Delete rate

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful creation
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent rate
- [ ] Business Logic: Test shipping cost calculations

### 46. SystemSettingController
**Methods to test:**
- `index()` - Get system settings
- `store()` - Create setting
- `show($setting)` - Get specific setting
- `update($setting)` - Update setting
- `destroy($setting)` - Delete setting

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with super admin permissions only
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful creation
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with regular user (should fail)
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent setting

### 47. UserActivityController
**Methods to test:**
- `index()` - Get user activities
- `show($activity)` - Get specific activity

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test that users can only access their own activities
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful specific activity retrieval
- [ ] Error: Test with unauthenticated user
- [ ] Error: Test accessing another user's activities
- [ ] Error: Test with non-existent activity

### 48. UserController
**Methods to test:**
- `index()` - Get all users
- `store()` - Create user
- `show($user)` - Get specific user
- `update($user)` - Update user
- `destroy($user)` - Delete user
- `updateProfile()` - Update profile
- `deleteAccount()` - Delete account
- `showProfile()` - Show profile

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful user retrieval
- [ ] Success: Test successful user creation
- [ ] Success: Test successful user update
- [ ] Success: Test successful user deletion
- [ ] Success: Test successful profile update
- [ ] Success: Test successful profile retrieval
- [ ] Success: Test successful account deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent user
- [ ] Business Logic: Test unique constraints

### 49. UserNotificationController
**Methods to test:**
- `index()` - Get notifications
- `show($notification)` - Get specific notification
- `update($notification)` - Update notification
- `destroy($notification)` - Delete notification

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test that users can only access their own notifications
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful retrieval of specific notification
- [ ] Success: Test successful notification update (read/unread)
- [ ] Success: Test successful notification deletion
- [ ] Error: Test with unauthenticated user
- [ ] Error: Test accessing another user's notifications
- [ ] Error: Test with non-existent notification

### 50. VerificationController
**Methods to test:**
- `index()` - Get verifications
- `store()` - Create verification
- `show($verification)` - Get specific verification
- `update($verification)` - Update verification
- `destroy($verification)` - Delete verification

**Test scenarios:**
- [ ] Authentication: Test with authenticated user
- [ ] Authorization: Test with appropriate permissions
- [ ] Validation: Test all validation rules
- [ ] Success: Test successful retrieval
- [ ] Success: Test successful creation
- [ ] Success: Test successful update
- [ ] Success: Test successful deletion
- [ ] Error: Test with insufficient permissions
- [ ] Error: Test with invalid data
- [ ] Error: Test with non-existent verification
- [ ] Business Logic: Test verification code validation

## Test Implementation Workflow

For each controller, implement tests following this detailed step-by-step checklist:

### Phase 1: Setup and Configuration
- [ ] Create test class file in `tests/Feature/Api/` directory
- [ ] Name the file following convention: `[ControllerName]Test.php`
- [ ] Import necessary classes and traits
- [ ] Implement `RefreshDatabase` trait for database reset
- [ ] Implement `WithFaker` trait for generating test data
- [ ] Create setUp method to initialize common test objects

### Phase 2: Authentication Testing
- [ ] Create test for unauthenticated access to GET endpoints
- [ ] Create test for unauthenticated access to POST endpoints
- [ ] Create test for unauthenticated access to PUT/PATCH endpoints
- [ ] Create test for unauthenticated access to DELETE endpoints
- [ ] Create helper method for authenticated requests
- [ ] Test with valid authentication tokens
- [ ] Test with expired authentication tokens
- [ ] Test with invalid authentication tokens

### Phase 3: Authorization Testing
- [ ] Test with insufficient permissions
- [ ] Test with proper permissions
- [ ] Test resource ownership validation
- [ ] Test accessing resources belonging to other users
- [ ] Create helper methods for different user roles

### Phase 4: Validation Testing
- [ ] Test all required fields validation
- [ ] Test with missing required fields
- [ ] Test data type validation
- [ ] Test field length validation
- [ ] Test numerical range validation
- [ ] Test unique constraints
- [ ] Test format validation (email, phone, etc.)
- [ ] Test with invalid data types

### Phase 5: Functional Testing
- [ ] Test successful GET requests (index, show)
- [ ] Test successful POST requests (store)
- [ ] Test successful PUT/PATCH requests (update)
- [ ] Test successful DELETE requests (destroy)
- [ ] Test pagination if applicable
- [ ] Test filtering if applicable
- [ ] Test sorting if applicable
- [ ] Test with valid data

### Phase 6: Error Handling Testing
- [ ] Test with invalid resource IDs
- [ ] Test with non-existent resources
- [ ] Test with malformed JSON
- [ ] Test with database connection errors
- [ ] Test with unexpected exceptions
- [ ] Test error response format consistency

### Phase 7: Business Logic Testing
- [ ] Test workflow constraints
- [ ] Test data integrity rules
- [ ] Test relationship constraints
- [ ] Test data calculation accuracy
- [ ] Test business rule enforcement
- [ ] Test edge cases and boundary conditions

### Phase 8: Integration Testing
- [ ] Test controller with related services
- [ ] Test controller with middleware
- [ ] Test with event listeners
- [ ] Test with queued jobs
- [ ] Test with external API calls (using mocks)

## Common Test Categories for All Controllers

### Authentication Tests
- [ ] Test endpoints without authentication (401 Unauthorized)
- [ ] Test endpoints with valid authentication tokens
- [ ] Test endpoints with expired authentication tokens
- [ ] Test endpoints with invalid authentication tokens

### Authorization Tests
- [ ] Test endpoints with insufficient permissions
- [ ] Test endpoints with proper permissions
- [ ] Test accessing resources belonging to other users

### Validation Tests
- [ ] Test all validation rules with valid data
- [ ] Test all validation rules with invalid data
- [ ] Test required fields validation
- [ ] Test unique constraints
- [ ] Test data type validation
- [ ] Test field length validation
- [ ] Test numerical range validation

### Error Handling Tests
- [ ] Test with invalid resource IDs
- [ ] Test with non-existent resources
- [ ] Test with malformed JSON
- [ ] Test with database connection errors
- [ ] Test with unexpected exceptions

### Success Path Tests
- [ ] Test successful creation with valid data
- [ ] Test successful retrieval of resources
- [ ] Test successful update with valid data
- [ ] Test successful deletion
- [ ] Test pagination if applicable
- [ ] Test filtering if applicable
- [ ] Test sorting if applicable

### Business Logic Tests
- [ ] Test workflow constraints
- [ ] Test data integrity rules
- [ ] Test permissions and access controls
- [ ] Test relationship constraints
- [ ] Test data calculation accuracy

## Testing Implementation Priorities

### Priority 1 (High)
- AuthController - Critical for security
- UserController - Core user management
- ProductController - Core product functionality
- OrderController - Core business transactions
- PaymentTransactionController - Financial transactions

### Priority 2 (Medium)
- CartController - Important for user experience
- Chat controllers - Communication features
- Review controllers - User feedback system
- Seller related controllers - Business partnerships

### Priority 3 (Low)
- Administrative and reporting controllers
- Support system controllers
- Configuration and setting controllers

## Test Data Strategy

### Database Setup
- Use `RefreshDatabase` trait to reset database between tests
- Create data factories for each model
- Seed minimal data required for testing
- Use database transactions to maintain data integrity

### Mocking Strategy
- Mock external services (email, SMS, payment gateways)
- Mock third-party APIs (Google, social media)
- Use in-memory cache for testing
- Mock file storage services

## Test Execution Guidelines

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=AuthControllerTest

# Run tests with coverage
php artisan test --coverage
```

### Code Coverage Target
- Aim for minimum 80% code coverage
- Focus on critical business logic
- Test edge cases and error conditions
- Ensure all validation rules are tested

## Documentation References

For implementation details, refer to:
- Laravel Testing Documentation
- Application-specific business requirements
- API specification documents
- Database schema documentation