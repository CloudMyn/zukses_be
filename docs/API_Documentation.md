# API Documentation - Zukses E-commerce Platform

## Table of Contents
- [Base URL & Authentication](#base-url--authentication)
- [Authentication API](#authentication-api)
- [User Management API](#user-management-api)
- [Response Format](#response-format)
- [Error Codes](#error-codes)

## Base URL & Authentication

**Base URL**: `https://your-api-domain/api/`

### Authentication Methods

The API uses Laravel Sanctum for authentication with personal access tokens. There are two types of authentication required across different endpoints:

1. **Public Endpoints**: No authentication required
2. **Protected Endpoints**: Requires Bearer token in the Authorization header
   - Header: `Authorization: Bearer {token}`

## Authentication API

### Registration Endpoint

**Method**: `POST`  
**Endpoint**: `/api/auth/register`  
**Authentication**: None

Registers a new user account with the system. The system will automatically generate a random password and send it to the user's contact (WhatsApp priority or email).

#### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| email | string | Yes | Email address (must be unique) |

#### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Registrasi berhasil. Password telah dikirim ke kontak Anda.",
  "data": {
    "user": {...}, // User resource object
    "token": "sanctum_token_string"
  }
}
```

**Error (400/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

### Login Endpoint

**Method**: `POST`  
**Endpoint**: `/api/auth/login`  
**Authentication**: None

Authenticates a user and returns an access token. Authentication can be done using either OTP code or password.

#### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| contact | string | Yes | Email address or phone number |
| password | string | No | Password (required if otp_code not provided) |
| otp_code | string | No | 6-digit OTP code (required if password not provided) |
| device_id | string | No | Unique device identifier |
| device_name | string | No | Name of the device |
| operating_system | string | No | Operating system of the device |
| app_version | string | No | App version |
| push_token | string | No | Push notification token |

#### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "user": {...}, // User resource object
    "token": "sanctum_token_string",
    "device": {...} // Device resource object (null if device_id not provided)
  }
}
```

**Error (400/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

### Send OTP Endpoint

**Method**: `POST`  
**Endpoint**: `/api/auth/send-otp`  
**Authentication**: None

Sends a one-time password (OTP) to the user for verification or login.

#### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| contact | string | Yes | Email address or phone number |

#### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Kode OTP telah dikirim ke email/nomor telepon : {otp_code}",
  "data": {
    "verification_id": "verification_id",
    "expires_at": "expiration_datetime",
    "verification_type": "EMAIL/TELEPON"
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Forgot Password Endpoint

**Method**: `POST`  
**Endpoint**: `/api/auth/forgot-password`  
**Authentication**: None

Initiates the password reset process by sending OTP to user contact.

#### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| contact | string | Yes | Email address or phone number |

#### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Kode OTP telah dikirim untuk reset password",
  "data": {
    "verification_id": "verification_id",
    "expires_at": "expiration_datetime",
    "verification_type": "EMAIL/TELEPON"
  }
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Verify OTP Endpoint

**Method**: `POST`  
**Endpoint**: `/api/auth/verify-otp`  
**Authentication**: None

Verifies the OTP code sent to user contact.

#### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| contact | string | Yes | Email address or phone number |
| otp_code | string | Yes | 6-digit OTP code |

#### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "OTP terverifikasi, silakan atur password baru",
  "data": {
    "user_id": "user_id"
  }
}
```

**Error (400/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Reset Password Endpoint

**Method**: `POST`  
**Endpoint**: `/api/auth/reset-password`  
**Authentication**: None

Resets the user password after OTP verification.

#### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| user_id | integer | Yes | User ID (must exist in users table) |
| new_password | string | Yes | New password (min: 8 characters, must be confirmed) |

#### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Password berhasil direset"
}
```

**Error (422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

### Google Authentication Redirect

**Method**: `GET`  
**Endpoint**: `/api/auth/google`  
**Authentication**: None

Redirects user to Google for OAuth authentication.

#### Response:
Redirect to Google OAuth page

---

### Google Authentication Callback

**Method**: `GET`  
**Endpoint**: `/api/auth/google/callback`  
**Authentication**: None

Handles the callback from Google OAuth and creates/updates user account.

#### Query Parameters (handled by Socialite):
- `code` (Google provides this after user grants permission)

#### Request Body (optional):

| Field | Type | Description |
|-------|------|-------------|
| device_id | string | Device identifier |
| device_name | string | Name of the device |
| operating_system | string | Operating system |
| app_version | string | App version |
| push_token | string | Push notification token |

#### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Login dengan Google berhasil",
  "data": {
    "user": {...}, // User resource object
    "token": "sanctum_token_string",
    "device": {...} // Device resource object if provided
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Logout Endpoint

**Method**: `POST`  
**Endpoint**: `/api/auth/logout`  
**Authentication**: Bearer token (Laravel Sanctum)

Logs out the authenticated user by invalidating the current token.

#### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

#### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Logout berhasil"
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Get Current User Endpoint

**Method**: `GET`  
**Endpoint**: `/api/auth/me`  
**Authentication**: Bearer token (Laravel Sanctum)

Retrieves the authenticated user's profile information.

#### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

#### Response:

**Success (200):**
```json
{
  "success": true,
  "data": {...} // User resource object
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Update Profile Endpoint

**Method**: `PUT`  
**Endpoint**: `/api/auth/profile`  
**Authentication**: Bearer token (Laravel Sanctum)

Updates the authenticated user's profile information.

#### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

#### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| username | string | New username |
| nama_depan | string | First name |
| nama_belakang | string | Last name |
| jenis_kelamin | string | Gender (LAKI_LAKI/PEREMPUAN/RAHASIA) |
| tanggal_lahir | date | Date of birth |
| bio | string | User biography |
| pengaturan | json | User settings |
| url_media_sosial | json | Social media URLs |
| bidang_interests | json | Field of interests |
| kata_sandi | string | New password (min: 8 characters) |

#### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Profil berhasil diperbarui",
  "data": {...} // Updated user resource object
}
```

**Error (422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

### Delete Account Endpoint

**Method**: `DELETE`  
**Endpoint**: `/api/auth/delete-account`  
**Authentication**: Bearer token (Laravel Sanctum)

Permanently deletes the authenticated user's account after optional password verification.

#### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

#### Request Body (optional):

| Field | Type | Description |
|-------|------|-------------|
| password | string | Password for verification (required) |

#### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Akun berhasil dihapus"
}
```

**Error (400/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Get Profile Endpoint

**Method**: `GET`  
**Endpoint**: `/api/auth/profile`  
**Authentication**: Bearer token (Laravel Sanctum)

Retrieves the authenticated user's profile information.

#### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

#### Response:

**Success (200):**
```json
{
  "success": true,
  "data": {...} // User resource object
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## User Management API

### User Resource Endpoints

**Base Endpoint**: `/api/users`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for user management.

#### GET /api/users - List all users (Admin only)

List all users with optional filtering and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter users |
| status | string | No | Filter by user status (AKTIF/TIDAK_AKTIF/DIBLOKIR/SUSPEND) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pengguna berhasil diambil",
  "data": [...], // Array of user resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (403/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/users - Create new user

Create a new user account (Admin only).

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| username | string | Yes | Unique username |
| email | string | Yes | Unique email address |
| nomor_telepon | string | No | Unique phone number |
| kata_sandi | string | Yes | Password (min: 8 characters) |
| tipe_user | string | Yes | User type (ADMIN/PELANGGAN/PEDAGANG) |
| status | string | Yes | User status (AKTIF/TIDAK_AKTIF/DIBLOKIR/SUSPEND) |
| nama_depan | string | No | First name (max: 255 characters) |
| nama_belakang | string | No | Last name (max: 255 characters) |
| jenis_kelamin | string | No | Gender (LAKI_LAKI/PEREMPUAN/RAHASIA) |
| tanggal_lahir | date | No | Date of birth |
| bio | string | No | Biography (max: 500 characters) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Pengguna berhasil dibuat",
  "data": {...} // Created user resource object
}
```

**Error (422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/users/{id} - Get specific user

Retrieve a specific user's information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | User ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pengguna berhasil diambil",
  "data": {...} // User resource object
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/users/{id} - Update user

Update user information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | User ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| username | string | Unique username |
| email | string | Unique email address |
| nomor_telepon | string | Unique phone number |
| kata_sandi | string | Password (min: 8 characters) |
| tipe_user | string | User type (ADMIN/PELANGGAN/PEDAGANG) |
| status | string | User status (AKTIF/TIDAK_AKTIF/DIBLOKIR/SUSPEND) |
| nama_depan | string | First name (max: 255 characters) |
| nama_belakang | string | Last name (max: 255 characters) |
| jenis_kelamin | string | Gender (LAKI_LAKI/PEREMPUAN/RAHASIA) |
| tanggal_lahir | date | Date of birth |
| bio | string | Biography (max: 500 characters) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pengguna berhasil diperbarui",
  "data": {...} // Updated user resource object
}
```

**Error (404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/users/{id} - Delete user

Delete a specific user account.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | User ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pengguna berhasil dihapus"
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Product Management API

### Product Resource Endpoints

**Base Endpoint**: `/api/products`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for product management with filtering and search capabilities.

#### GET /api/products - List all products

List all products with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter products by name, description, or brand |
| category | string | No | Filter by product category |
| min_price | number | No | Filter by minimum price |
| max_price | number | No | Filter by maximum price |
| seller_id | integer | No | Filter by specific seller |
| status | string | No | Filter by product status (AKTIF/TIDAK_AKTIF/TERJUAL/HABIS) |
| sort_by | string | No | Sort by field (created_at/updated_at/harga/nama_produk) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data produk berhasil diambil",
  "data": [...], // Array of product resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/products - Create new product

Create a new product listing (Seller only).

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| nama_produk | string | Yes | Product name (max: 255 characters) |
| deskripsi | text | No | Product description |
| harga | number | Yes | Product price |
| stok | integer | Yes | Product stock quantity |
| kategori_id | integer | No | Category ID (if applicable) |
| berat | number | No | Product weight in grams |
| dimensi_panjang | number | No | Product length in cm |
| dimensi_lebar | number | No | Product width in cm |
| dimensi_tinggi | number | No | Product height in cm |
| brand | string | No | Product brand |
| sku | string | No | SKU/Stock Keeping Unit identifier |
| kondisi | string | No | Product condition (BARU/BEKAS/REFURBISHED) |
| status | string | Yes | Product status (DRAFT/AKTIF/TIDAK_AKTIF/TERJUAL/HABIS) |
| foto_produk | array | No | Array of product image URLs |
| varian_produk | json | No | Product variants if applicable |
| kebijakan_pengembalian | text | No | Return policy for the product |
| garansi | string | No | Warranty information |
| jumlah_terjual | integer | No | Number of items sold (default: 0) |
| jumlah_dilihat | integer | No | Number of views (default: 0) |
| jumlah_difavoritkan | integer | No | Number of times favorited (default: 0) |
| rating_produk | number | No | Product rating (0.00 to 5.00, default: 0.00) |
| jumlah_ulasan | integer | No | Number of reviews (default: 0) |
| is_produk_unggulan | boolean | No | Whether product is featured (default: false) |
| is_produk_preorder | boolean | No | Whether product is preorder (default: false) |
| is_cod | boolean | No | Whether COD is available (default: false) |
| is_approved | boolean | No | Whether product is approved (default: false) |
| stok_minimum | integer | No | Minimum stock level (default: 0) |
| harga_minimum | number | No | Minimum price (default: 0.00) |
| harga_maximum | number | No | Maximum price (default: 0.00) |
| id_seller | integer | Yes | Seller ID (must exist in penjual table) |
| id_admin | integer | No | Admin ID (must exist in users table) |
| slug_produk | string | Yes | Product slug (must be unique) |
| deskripsi_lengkap | text | No | Full product description |
| tag_produk | json | No | Product tags |
| meta_title | string | No | SEO meta title |
| meta_description | string | No | SEO meta description |
| video_produk | string | No | Product video URL |
| tanggal_dipublikasikan | datetime | No | Publication date |
| is_product_varian | boolean | No | Whether product has variants (default: false) |
| waktu_preorder | integer | No | Preorder time in days |
| etalase_kategori | string | No | Product category shelf |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Produk berhasil dibuat",
  "data": {...} // Created product resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/products/{id} - Get specific product

Retrieve a specific product's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Product ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data produk berhasil diambil",
  "data": {...} // Product resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/products/{id} - Update product

Update product information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Product ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| nama_produk | string | Product name (max: 255 characters) |
| deskripsi | text | Product description |
| harga | number | Product price |
| stok | integer | Product stock quantity |
| kategori_id | integer | Category ID (if applicable) |
| berat | number | Product weight in grams |
| dimensi_panjang | number | Product length in cm |
| dimensi_lebar | number | Product width in cm |
| dimensi_tinggi | number | Product height in cm |
| brand | string | Product brand |
| sku | string | SKU/Stock Keeping Unit identifier |
| kondisi | string | Product condition (BARU/BEKAS) |
| status | string | Product status (AKTIF/TIDAK_AKTIF) |
| foto_produk | array | Array of product image URLs |
| varian_produk | json | Product variants if applicable |
| kebijakan_pengembalian | text | Return policy for the product |
| garansi | string | Warranty information |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Produk berhasil diperbarui",
  "data": {...} // Updated product resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/products/{id} - Delete product

Delete a specific product listing.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Product ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Produk berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### GET /api/products/categories - Get all product categories

Retrieve all available product categories.

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data kategori produk berhasil diambil",
  "data": [...], // Array of category objects
  "pagination": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 15,
    "total": 42
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/products/{id}/reviews - Add product review

Add a review for a specific product.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Product ID |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| rating | integer | Yes | Rating score (1-5) |
| komentar | string | No | Review comment |
| judul | string | No | Review title |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Ulasan produk berhasil ditambahkan",
  "data": {...} // Created review resource object
}
```

**Error (401/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/products/{id}/reviews - Get product reviews

Retrieve all reviews for a specific product.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Product ID |

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| sort_by | string | No | Sort by field (created_at/rating) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Ulasan produk berhasil diambil",
  "data": [...], // Array of review objects
  "pagination": {
    "current_page": 1,
    "last_page": 2,
    "per_page": 15,
    "total": 28
  }
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### GET /api/products/search - Search products

Search for products with advanced filtering options.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| q | string | Yes | Search query term |
| category | string | No | Filter by product category |
| min_price | number | No | Filter by minimum price |
| max_price | number | No | Filter by maximum price |
| seller_id | integer | No | Filter by specific seller |
| sort_by | string | No | Sort by field (relevance/created_at/updated_at/harga) |
| sort_order | string | No | Sort order (asc/desc) |
| per_page | integer | No | Number of items per page (default: 15) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Hasil pencarian produk",
  "data": [...], // Array of matching product objects
  "pagination": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 15,
    "total": 42
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Product Categories Resource Endpoints

**Base Endpoint**: `/api/categories`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for product categories.

#### GET /api/categories - List all product categories

List all product categories with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter categories by name |
| sort_by | string | No | Sort by field (created_at/updated_at/nama_kategori) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data kategori produk berhasil diambil",
  "data": [...], // Array of category resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/categories - Create new product category

Create a new product category.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| nama_kategori | string | Yes | Category name (must be unique) |
| slug_kategori | string | Yes | Category slug (must be unique) |
| deskripsi_kategori | string | No | Category description |
| gambar_kategori | string | No | Category image URL |
| status_kategori | string | No | Category status (AKTIF/TIDAK_AKTIF) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Kategori produk berhasil dibuat",
  "data": {...} // Created category resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/categories/{id} - Get specific product category

Retrieve a specific product category's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Category ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data kategori produk berhasil diambil",
  "data": {...} // Category resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/categories/{id} - Update product category

Update product category information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Category ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| nama_kategori | string | Category name (must be unique) |
| slug_kategori | string | Category slug (must be unique) |
| deskripsi_kategori | string | Category description |
| gambar_kategori | string | Category image URL |
| status_kategori | string | Category status (AKTIF/TIDAK_AKTIF) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Kategori produk berhasil diperbarui",
  "data": {...} // Updated category resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/categories/{id} - Delete product category

Delete a specific product category.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Category ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Kategori produk berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Product Variant Resource Endpoints

**Base Endpoint**: `/api/product-variants`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for product variants.

#### GET /api/product-variants - List all product variants

List all product variants with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter variants by name |
| produk_id | integer | No | Filter by specific product |
| sort_by | string | No | Sort by field (created_at/updated_at/nama_varian) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data varian produk berhasil diambil",
  "data": [...], // Array of variant resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/product-variants - Create new product variant

Create a new product variant.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| produk_id | integer | Yes | Product ID (must exist in tb_produk) |
| nama_varian | string | Yes | Variant name |
| urutan | integer | Yes | Display order (min: 0) |
| deskripsi_varian | string | No | Variant description |
| status_varian | string | No | Variant status (AKTIF/TIDAK_AKTIF) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Varian produk berhasil dibuat",
  "data": {...} // Created variant resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/product-variants/{id} - Get specific product variant

Retrieve a specific product variant's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Variant ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data varian produk berhasil diambil",
  "data": {...} // Variant resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/product-variants/{id} - Update product variant

Update product variant information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Variant ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| produk_id | integer | Product ID (must exist in tb_produk) |
| nama_varian | string | Variant name |
| urutan | integer | Display order (min: 0) |
| deskripsi_varian | string | Variant description |
| status_varian | string | Variant status (AKTIF/TIDAK_AKTIF) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Varian produk berhasil diperbarui",
  "data": {...} // Updated variant resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/product-variants/{id} - Delete product variant

Delete a specific product variant.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Variant ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Varian produk berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Product Variant Values Resource Endpoints

**Base Endpoint**: `/api/product-variant-values`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for product variant values.

#### GET /api/product-variant-values - List all product variant values

List all product variant values with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter values by name |
| varian_id | integer | No | Filter by specific variant |
| sort_by | string | No | Sort by field (created_at/updated_at/nilai) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data nilai varian produk berhasil diambil",
  "data": [...], // Array of variant value resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/product-variant-values - Create new product variant value

Create a new product variant value.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| varian_id | integer | Yes | Variant ID (must exist in varian_produk) |
| nilai | string | Yes | Variant value |
| urutan | integer | Yes | Display order (min: 0) |
| deskripsi_nilai | string | No | Value description |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Nilai varian produk berhasil dibuat",
  "data": {...} // Created variant value resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/product-variant-values/{id} - Get specific product variant value

Retrieve a specific product variant value's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Variant value ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data nilai varian produk berhasil diambil",
  "data": {...} // Variant value resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/product-variant-values/{id} - Update product variant value

Update product variant value information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Variant value ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| varian_id | integer | Variant ID (must exist in varian_produk) |
| nilai | string | Variant value |
| urutan | integer | Display order (min: 0) |
| deskripsi_nilai | string | Value description |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Nilai varian produk berhasil diperbarui",
  "data": {...} // Updated variant value resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/product-variant-values/{id} - Delete product variant value

Delete a specific product variant value.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Variant value ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Nilai varian produk berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Product Variant Prices Resource Endpoints

**Base Endpoint**: `/api/product-variant-prices`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for product variant prices.

#### GET /api/product-variant-prices - List all product variant prices

List all product variant prices with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter prices |
| produk_id | integer | No | Filter by specific product |
| sort_by | string | No | Sort by field (created_at/updated_at/harga) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data harga varian produk berhasil diambil",
  "data": [...], // Array of variant price resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/product-variant-prices - Create new product variant price

Create a new product variant price.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| produk_id | integer | Yes | Product ID (must exist in tb_produk) |
| gambar | string | No | Price variant image URL |
| harga | number | Yes | Price value (min: 0) |
| stok | integer | No | Stock quantity |
| min_pembelian | integer | No | Minimum purchase quantity |
| berat | number | No | Item weight in grams |
| panjang | number | No | Item length in cm |
| lebar | number | No | Item width in cm |
| tinggi | number | No | Item height in cm |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Harga varian produk berhasil dibuat",
  "data": {...} // Created variant price resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/product-variant-prices/{id} - Get specific product variant price

Retrieve a specific product variant price's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Variant price ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data harga varian produk berhasil diambil",
  "data": {...} // Variant price resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/product-variant-prices/{id} - Update product variant price

Update product variant price information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Variant price ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| produk_id | integer | Product ID (must exist in tb_produk) |
| gambar | string | Price variant image URL |
| harga | number | Price value (min: 0) |
| stok | integer | Stock quantity |
| min_pembelian | integer | Minimum purchase quantity |
| berat | number | Item weight in grams |
| panjang | number | Item length in cm |
| lebar | number | Item width in cm |
| tinggi | number | Item height in cm |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Harga varian produk berhasil diperbarui",
  "data": {...} // Updated variant price resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/product-variant-prices/{id} - Delete product variant price

Delete a specific product variant price.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Variant price ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Harga varian produk berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Seller Management API

### Seller Resource Endpoints

**Base Endpoint**: `/api/sellers`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for seller management.

#### GET /api/sellers - List all sellers

List all sellers with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter sellers by name or shop name |
| status | string | No | Filter by seller status (AKTIF/TIDAK_AKTIF/DIBLOKIR) |
| sort_by | string | No | Sort by field (created_at/updated_at/nama_toko) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data penjual berhasil diambil",
  "data": [...], // Array of seller resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/sellers - Create new seller

Create a new seller account.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_user | integer | Yes | User ID (must exist in users table) |
| nama_toko | string | Yes | Shop name (max: 255 characters) |
| deskripsi_toko | string | No | Shop description |
| alamat_toko | string | No | Shop address |
| nomor_telepon_toko | string | No | Shop contact number |
| status_toko | string | No | Seller status (AKTIF/TIDAK_AKTIF/DIBLOKIR) |
| rating | number | No | Seller rating (0.00 to 5.00) |
| jumlah_transaksi | integer | No | Number of completed transactions |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Penjual berhasil dibuat",
  "data": {...} // Created seller resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/sellers/{id} - Get specific seller

Retrieve a specific seller's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Seller ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data penjual berhasil diambil",
  "data": {...} // Seller resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/sellers/{id} - Update seller

Update seller information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Seller ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| nama_toko | string | Shop name (max: 255 characters) |
| deskripsi_toko | string | Shop description |
| alamat_toko | string | Shop address |
| nomor_telepon_toko | string | Shop contact number |
| status_toko | string | Seller status (AKTIF/TIDAK_AKTIF/DIBLOKIR) |
| rating | number | Seller rating (0.00 to 5.00) |
| jumlah_transaksi | integer | Number of completed transactions |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Penjual berhasil diperbarui",
  "data": {...} // Updated seller resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/sellers/{id} - Delete seller

Delete a specific seller account.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Seller ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Penjual berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Device Management API

### Device Resource Endpoints

**Base Endpoint**: `/api/devices`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for user device management.

#### GET /api/devices - List all devices

List all devices with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter devices by name or OS |
| user_id | integer | No | Filter by specific user |
| status | string | No | Filter by device status (TERPERCAYA/TIDAK_TERPERCAYA) |
| sort_by | string | No | Sort by field (created_at/updated_at/device_name) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data perangkat berhasil diambil",
  "data": [...], // Array of device resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/devices - Create new device

Register a new device for a user.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| device_id | string | Yes | Unique device identifier |
| id_user | integer | Yes | User ID (must exist in users table) |
| device_name | string | Yes | Device name |
| operating_system | string | Yes | Operating system |
| app_version | string | No | Application version |
| push_token | string | No | Push notification token |
| adalah_device_terpercaya | boolean | No | Whether device is trusted (default: false) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Perangkat berhasil didaftarkan",
  "data": {...} // Created device resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/devices/{id} - Get specific device

Retrieve a specific device's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Device ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data perangkat berhasil diambil",
  "data": {...} // Device resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/devices/{id} - Update device

Update device information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Device ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| device_id | string | Unique device identifier |
| id_user | integer | User ID (must exist in users table) |
| device_name | string | Device name |
| operating_system | string | Operating system |
| app_version | string | Application version |
| push_token | string | Push notification token |
| adalah_device_terpercaya | boolean | Whether device is trusted |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Perangkat berhasil diperbarui",
  "data": {...} // Updated device resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/devices/{id} - Delete device

Delete a specific device registration.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Device ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Perangkat berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Trust Device Endpoint

**Method**: `POST`  
**Endpoint**: `/api/devices/{id}/trust`  
**Authentication**: Bearer token (Laravel Sanctum)

Mark a device as trusted by the user.

#### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

#### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Device ID |

#### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Perangkat berhasil ditandai sebagai terpercaya"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Verification Management API

### Verification Resource Endpoints

**Base Endpoint**: `/api/verifications`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for user verification management.

#### GET /api/verifications - List all verifications

List all verifications with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| user_id | integer | No | Filter by specific user |
| jenis_verifikasi | string | No | Filter by verification type (EMAIL/TELEPON/KTP/NPWP) |
| status | string | No | Filter by verification status (TERVERIFIKASI/TIDAK_TERVERIFIKASI/KADALUWARSA) |
| sort_by | string | No | Sort by field (created_at/updated_at/kedaluwarsa_pada) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data verifikasi berhasil diambil",
  "data": [...], // Array of verification resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/verifications - Create new verification

Create a new verification record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_user | integer | No | User ID (must exist in users table) |
| jenis_verifikasi | string | Yes | Verification type (EMAIL/TELEPON/KTP/NPWP) |
| nilai_verifikasi | string | Yes | Verification value (email/phone number/etc.) |
| kode_verifikasi | string | Yes | 6-digit verification code |
| kedaluwarsa_pada | datetime | Yes | Expiration datetime for the code |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Verifikasi berhasil dibuat",
  "data": {...} // Created verification resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/verifications/{id} - Get specific verification

Retrieve a specific verification's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Verification ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data verifikasi berhasil diambil",
  "data": {...} // Verification resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/verifications/{id} - Update verification

Update verification information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Verification ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| jenis_verifikasi | string | Verification type (EMAIL/TELEPON/KTP/NPWP) |
| nilai_verifikasi | string | Verification value (email/phone number/etc.) |
| kode_verifikasi | string | 6-digit verification code |
| kedaluwarsa_pada | datetime | Expiration datetime for the code |
| telah_digunakan | boolean | Whether the code has been used |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Verifikasi berhasil diperbarui",
  "data": {...} // Updated verification resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/verifications/{id} - Delete verification

Delete a specific verification record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Verification ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Verifikasi berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Session Management API

### Session Resource Endpoints

**Base Endpoint**: `/api/sessions`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for user session management.

#### GET /api/sessions - List all sessions

List all sessions with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter sessions by IP or user agent |
| user_id | integer | No | Filter by specific user |
| ip_address | string | No | Filter by IP address |
| sort_by | string | No | Sort by field (last_activity/created_at) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data sesi berhasil diambil",
  "data": [...], // Array of session resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/sessions - Create new session

Create a new session record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id | string | Yes | Session ID (must be unique) |
| id_user | integer | No | User ID (must exist in users table) |
| ip_address | string | No | IP address (max: 45 characters) |
| user_agent | string | No | User agent string |
| payload | string | Yes | Session payload |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Sesi berhasil dibuat",
  "data": {...} // Created session resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/sessions/{id} - Get specific session

Retrieve a specific session's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | string | Session ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data sesi berhasil diambil",
  "data": {...} // Session resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/sessions/{id} - Update session

Update session information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | string | Session ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| ip_address | string | IP address (max: 45 characters) |
| user_agent | string | User agent string |
| payload | string | Session payload |
| last_activity | integer | Last activity timestamp |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Sesi berhasil diperbarui",
  "data": {...} // Updated session resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/sessions/{id} - Delete session

Delete a specific session record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | string | Session ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Sesi berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Address & Location APIs

### Address Resource Endpoints

**Base Endpoint**: `/api/addresses`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for user address management.

#### GET /api/addresses - List all addresses

List all addresses with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| user_id | integer | No | Filter by specific user |
| search | string | No | Search term to filter addresses by name or details |
| is_primary | boolean | No | Filter by primary address status |
| sort_by | string | No | Sort by field (created_at/updated_at/nama_penerima) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data alamat berhasil diambil",
  "data": [...], // Array of address resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/addresses - Create new address

Create a new address record for a user.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_user | integer | Yes | User ID (must exist in users table) |
| label_alamat | string | Yes | Address label (max: 255 characters) |
| nama_penerima | string | Yes | Recipient name (max: 255 characters) |
| nomor_telepon_penerima | string | Yes | Recipient phone number |
| alamat_lengkap | string | Yes | Complete address |
| provinsi_id | integer | Yes | Province ID (must exist in master_provinsi) |
| kota_id | integer | Yes | City ID (must exist in master_kota) |
| kecamatan_id | integer | Yes | District ID (must exist in master_kecamatan) |
| kode_pos_id | integer | Yes | Postal code ID (must exist in master_kode_pos) |
| catatan_pengiriman | string | No | Shipping notes |
| is_primary | boolean | No | Whether this is the primary address |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Alamat berhasil dibuat",
  "data": {...} // Created address resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/addresses/{id} - Get specific address

Retrieve a specific address's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Address ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data alamat berhasil diambil",
  "data": {...} // Address resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/addresses/{id} - Update address

Update address information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Address ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| label_alamat | string | Address label (max: 255 characters) |
| nama_penerima | string | Recipient name (max: 255 characters) |
| nomor_telepon_penerima | string | Recipient phone number |
| alamat_lengkap | string | Complete address |
| provinsi_id | integer | Province ID (must exist in master_provinsi) |
| kota_id | integer | City ID (must exist in master_kota) |
| kecamatan_id | integer | District ID (must exist in master_kecamatan) |
| kode_pos_id | integer | Postal code ID (must exist in master_kode_pos) |
| catatan_pengiriman | string | Shipping notes |
| is_primary | boolean | Whether this is the primary address |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Alamat berhasil diperbarui",
  "data": {...} // Updated address resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/addresses/{id} - Delete address

Delete a specific address record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Address ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Alamat berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Province Resource Endpoints

**Base Endpoint**: `/api/provinces`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for province management.

#### GET /api/provinces - List all provinces

List all provinces with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter provinces by name |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data provinsi berhasil diambil",
  "data": [...], // Array of province resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/provinces - Create new province

Create a new province record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| nama | string | Yes | Province name (must be unique) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Provinsi berhasil dibuat",
  "data": {...} // Created province resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/provinces/{id} - Get specific province

Retrieve a specific province's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Province ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data provinsi berhasil diambil",
  "data": {...} // Province resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/provinces/{id} - Update province

Update province information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Province ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| nama | string | Province name (must be unique) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Provinsi berhasil diperbarui",
  "data": {...} // Updated province resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/provinces/{id} - Delete province

Delete a specific province record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Province ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Provinsi berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### City Resource Endpoints

**Base Endpoint**: `/api/cities`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for city management.

#### GET /api/cities - List all cities

List all cities with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter cities by name |
| provinsi_id | integer | No | Filter by specific province |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data kota berhasil diambil",
  "data": [...], // Array of city resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/cities - Create new city

Create a new city record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| provinsi_id | integer | Yes | Province ID (must exist in master_provinsi) |
| nama | string | Yes | City name |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Kota berhasil dibuat",
  "data": {...} // Created city resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/cities/{id} - Get specific city

Retrieve a specific city's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | City ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data kota berhasil diambil",
  "data": {...} // City resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/cities/{id} - Update city

Update city information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | City ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| provinsi_id | integer | Province ID (must exist in master_provinsi) |
| nama | string | City name |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Kota berhasil diperbarui",
  "data": {...} // Updated city resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/cities/{id} - Delete city

Delete a specific city record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | City ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Kota berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### District Resource Endpoints

**Base Endpoint**: `/api/districts`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for district management.

#### GET /api/districts - List all districts

List all districts with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter districts by name |
| kota_id | integer | No | Filter by specific city |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data kecamatan berhasil diambil",
  "data": [...], // Array of district resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/districts - Create new district

Create a new district record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| kota_id | integer | Yes | City ID (must exist in master_kota) |
| nama | string | Yes | District name |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Kecamatan berhasil dibuat",
  "data": {...} // Created district resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/districts/{id} - Get specific district

Retrieve a specific district's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | District ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data kecamatan berhasil diambil",
  "data": {...} // District resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/districts/{id} - Update district

Update district information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | District ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| kota_id | integer | City ID (must exist in master_kota) |
| nama | string | District name |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Kecamatan berhasil diperbarui",
  "data": {...} // Updated district resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/districts/{id} - Delete district

Delete a specific district record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | District ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Kecamatan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Postal Code Resource Endpoints

**Base Endpoint**: `/api/postal-codes`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for postal code management.

#### GET /api/postal-codes - List all postal codes

List all postal codes with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter postal codes |
| kecamatan_id | integer | No | Filter by specific district |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data kode pos berhasil diambil",
  "data": [...], // Array of postal code resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/postal-codes - Create new postal code

Create a new postal code record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| kecamatan_id | integer | Yes | District ID (must exist in master_kecamatan) |
| kode | string | Yes | Postal code (must be unique) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Kode pos berhasil dibuat",
  "data": {...} // Created postal code resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/postal-codes/{id} - Get specific postal code

Retrieve a specific postal code's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Postal code ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data kode pos berhasil diambil",
  "data": {...} // Postal code resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/postal-codes/{id} - Update postal code

Update postal code information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Postal code ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| kecamatan_id | integer | District ID (must exist in master_kecamatan) |
| kode | string | Postal code (must be unique) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Kode pos berhasil diperbarui",
  "data": {...} // Updated postal code resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/postal-codes/{id} - Delete postal code

Delete a specific postal code record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Postal code ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Kode pos berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Shopping & Order APIs

### Cart Resource Endpoints

**Base Endpoint**: `/api/carts`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for shopping cart management.

#### GET /api/carts - List all cart records

List all cart records with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| user_id | integer | No | Filter by specific user |
| seller_id | integer | No | Filter by specific seller |
| session_id | string | No | Filter by specific session |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data keranjang belanja berhasil diambil",
  "data": [...], // Array of cart resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/carts - Create new cart

Create a new cart record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_user | integer | No | User ID (must exist in users table) |
| session_id | string | No | Session ID |
| id_seller | integer | Yes | Seller ID (must exist in penjual table) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Keranjang belanja berhasil dibuat",
  "data": {...} // Created cart resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/carts/{id} - Get specific cart

Retrieve a specific cart's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Cart ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data keranjang belanja berhasil diambil",
  "data": {...} // Cart resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/carts/{id} - Update cart

Update cart information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Cart ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| session_id | string | Session ID |
| id_seller | integer | Seller ID (must exist in penjual table) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Keranjang belanja berhasil diperbarui",
  "data": {...} // Updated cart resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/carts/{id} - Delete cart

Delete a specific cart record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Cart ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Keranjang belanja berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Cart Item Resource Endpoints

**Base Endpoint**: `/api/cart-items`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for cart item management.

#### GET /api/cart-items - List all cart items

List all cart items with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| cart_id | integer | No | Filter by specific cart |
| product_id | integer | No | Filter by specific product |
| user_id | integer | No | Filter by specific user |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data item keranjang berhasil diambil",
  "data": [...], // Array of cart item resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/cart-items - Create new cart item

Create a new cart item record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_cart | integer | Yes | Cart ID (must exist in keranjang_belanja table) |
| id_produk | integer | Yes | Product ID (must exist in tb_produk table) |
| id_harga_varian | integer | No | Variant price ID (must exist in harga_varian_produk table) |
| jumlah | integer | Yes | Quantity (min: 1) |
| catatan | string | No | Item notes |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Item keranjang berhasil dibuat",
  "data": {...} // Created cart item resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/cart-items/{id} - Get specific cart item

Retrieve a specific cart item's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Cart item ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data item keranjang berhasil diambil",
  "data": {...} // Cart item resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/cart-items/{id} - Update cart item

Update cart item information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Cart item ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_cart | integer | Cart ID (must exist in keranjang_belanja table) |
| id_produk | integer | Product ID (must exist in tb_produk table) |
| id_harga_varian | integer | Variant price ID (must exist in harga_varian_produk table) |
| jumlah | integer | Quantity (min: 1) |
| catatan | string | Item notes |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Item keranjang berhasil diperbarui",
  "data": {...} // Updated cart item resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/cart-items/{id} - Delete cart item

Delete a specific cart item record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Cart item ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Item keranjang berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Order Resource Endpoints

**Base Endpoint**: `/api/orders`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for order management.

#### GET /api/orders - List all orders

List all orders with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| customer_id | integer | No | Filter by specific customer |
| seller_id | integer | No | Filter by specific seller |
| status | string | No | Filter by order status |
| nomor_pesanan | string | No | Filter by specific order number |
| sort_by | string | No | Sort by field (created_at/updated_at/tanggal_pesanan) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pesanan berhasil diambil",
  "data": [...], // Array of order resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/orders - Create new order

Create a new order record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| nomor_pesanan | string | Yes | Order number (must be unique) |
| id_customer | integer | Yes | Customer ID (must exist in users table) |
| id_alamat_pengiriman | integer | Yes | Shipping address ID (must exist in alamat table) |
| total_harga | number | Yes | Total price |
| jumlah_item | integer | Yes | Number of items in order |
| status_pesanan | string | No | Order status (default: DIPROSES) |
| tanggal_pesanan | datetime | No | Order date (default: now) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Pesanan berhasil dibuat",
  "data": {...} // Created order resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/orders/{id} - Get specific order

Retrieve a specific order's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pesanan berhasil diambil",
  "data": {...} // Order resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/orders/{id} - Update order

Update order information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| nomor_pesanan | string | Order number (must be unique) |
| id_customer | integer | Customer ID (must exist in users table) |
| id_alamat_pengiriman | integer | Shipping address ID (must exist in alamat table) |
| total_harga | number | Total price |
| jumlah_item | integer | Number of items in order |
| status_pesanan | string | Order status |
| tanggal_pesanan | datetime | Order date |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pesanan berhasil diperbarui",
  "data": {...} // Updated order resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/orders/{id} - Delete order

Delete a specific order record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pesanan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Order Item Resource Endpoints

**Base Endpoint**: `/api/order-items`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for order item management.

#### GET /api/order-items - List all order items

List all order items with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| order_id | integer | No | Filter by specific order |
| product_id | integer | No | Filter by specific product |
| seller_id | integer | No | Filter by specific seller |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data item pesanan berhasil diambil",
  "data": [...], // Array of order item resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/order-items - Create new order item

Create a new order item record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_pesanan | integer | Yes | Order ID (must exist in pesanan table) |
| id_seller | integer | Yes | Seller ID (must exist in penjual table) |
| id_produk | integer | Yes | Product ID (must exist in tb_produk table) |
| id_harga_varian | integer | No | Variant price ID (must exist in harga_varian_produk table) |
| jumlah | integer | Yes | Quantity |
| harga_satuan | number | Yes | Unit price |
| subtotal | number | Yes | Subtotal (quantity * unit price) |
| catatan | string | No | Item notes |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Item pesanan berhasil dibuat",
  "data": {...} // Created order item resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/order-items/{id} - Get specific order item

Retrieve a specific order item's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order item ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data item pesanan berhasil diambil",
  "data": {...} // Order item resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/order-items/{id} - Update order item

Update order item information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order item ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_pesanan | integer | Order ID (must exist in pesanan table) |
| id_seller | integer | Seller ID (must exist in penjual table) |
| id_produk | integer | Product ID (must exist in tb_produk table) |
| id_harga_varian | integer | Variant price ID (must exist in harga_varian_produk table) |
| jumlah | integer | Quantity |
| harga_satuan | number | Unit price |
| subtotal | number | Subtotal (quantity * unit price) |
| catatan | string | Item notes |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Item pesanan berhasil diperbarui",
  "data": {...} // Updated order item resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/order-items/{id} - Delete order item

Delete a specific order item record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order item ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Item pesanan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Payment & Shipping APIs

### Shipping Method Resource Endpoints

**Base Endpoint**: `/api/shipping-methods`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for shipping method management.

#### GET /api/shipping-methods - List all shipping methods

List all shipping methods with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter shipping methods |
| tipe_layanan | string | No | Filter by service type |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data metode pengiriman berhasil diambil",
  "data": [...], // Array of shipping method resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/shipping-methods - Create new shipping method

Create a new shipping method record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| nama_kurir | string | Yes | Courier name (max: 255 characters) |
| tipe_layanan | string | Yes | Service type |
| deskripsi_layanan | string | No | Service description |
| biaya_dasar | number | No | Base cost |
| estimasi_pengiriman | string | No | Delivery time estimate |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Metode pengiriman berhasil dibuat",
  "data": {...} // Created shipping method resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/shipping-methods/{id} - Get specific shipping method

Retrieve a specific shipping method's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Shipping method ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data metode pengiriman berhasil diambil",
  "data": {...} // Shipping method resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/shipping-methods/{id} - Update shipping method

Update shipping method information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Shipping method ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| nama_kurir | string | Courier name (max: 255 characters) |
| tipe_layanan | string | Service type |
| deskripsi_layanan | string | Service description |
| biaya_dasar | number | Base cost |
| estimasi_pengiriman | string | Delivery time estimate |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Metode pengiriman berhasil diperbarui",
  "data": {...} // Updated shipping method resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/shipping-methods/{id} - Delete shipping method

Delete a specific shipping method record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Shipping method ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Metode pengiriman berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Shipping Rate Resource Endpoints

**Base Endpoint**: `/api/shipping-rates`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for shipping rate management.

#### GET /api/shipping-rates - List all shipping rates

List all shipping rates with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| kurir_id | integer | No | Filter by specific courier |
| kota_asal_id | integer | No | Filter by specific origin city |
| kota_tujuan_id | integer | No | Filter by specific destination city |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data tarif pengiriman berhasil diambil",
  "data": [...], // Array of shipping rate resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/shipping-rates - Create new shipping rate

Create a new shipping rate record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_kurir | integer | Yes | Courier ID (must exist in metode_pengiriman table) |
| id_kota_asal | integer | Yes | Origin city ID (must exist in master_kota table) |
| id_kota_tujuan | integer | Yes | Destination city ID (must exist in master_kota table) |
| biaya_pengiriman | number | Yes | Shipping cost |
| estimasi_hari | integer | Yes | Estimated days for delivery |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Tarif pengiriman berhasil dibuat",
  "data": {...} // Created shipping rate resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/shipping-rates/{id} - Get specific shipping rate

Retrieve a specific shipping rate's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Shipping rate ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data tarif pengiriman berhasil diambil",
  "data": {...} // Shipping rate resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/shipping-rates/{id} - Update shipping rate

Update shipping rate information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Shipping rate ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_kurir | integer | Courier ID (must exist in metode_pengiriman table) |
| id_kota_asal | integer | Origin city ID (must exist in master_kota table) |
| id_kota_tujuan | integer | Destination city ID (must exist in master_kota table) |
| biaya_pengiriman | number | Shipping cost |
| estimasi_hari | integer | Estimated days for delivery |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Tarif pengiriman berhasil diperbarui",
  "data": {...} // Updated shipping rate resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/shipping-rates/{id} - Delete shipping rate

Delete a specific shipping rate record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Shipping rate ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Tarif pengiriman berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Order Shipping Resource Endpoints

**Base Endpoint**: `/api/order-shipping`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for order shipping management.

#### GET /api/order-shipping - List all order shipping records

List all order shipping records with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| order_id | integer | No | Filter by specific order |
| kurir_id | integer | No | Filter by specific courier |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pengiriman pesanan berhasil diambil",
  "data": [...], // Array of order shipping resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/order-shipping - Create new order shipping

Create a new order shipping record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_pesanan | integer | Yes | Order ID (must exist in pesanan table) |
| id_kurir | integer | Yes | Courier ID (must exist in metode_pengiriman table) |
| nama_kurir | string | Yes | Courier name (max: 255 characters) |
| layanan_kurir | string | No | Courier service type |
| biaya_pengiriman | number | Yes | Shipping cost |
| estimasi_pengiriman | string | No | Delivery time estimate |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Pengiriman pesanan berhasil dibuat",
  "data": {...} // Created order shipping resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/order-shipping/{id} - Get specific order shipping

Retrieve a specific order shipping's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order shipping ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pengiriman pesanan berhasil diambil",
  "data": {...} // Order shipping resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/order-shipping/{id} - Update order shipping

Update order shipping information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order shipping ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_pesanan | integer | Order ID (must exist in pesanan table) |
| id_kurir | integer | Courier ID (must exist in metode_pengiriman table) |
| nama_kurir | string | Courier name (max: 255 characters) |
| layanan_kurir | string | Courier service type |
| biaya_pengiriman | number | Shipping cost |
| estimasi_pengiriman | string | Delivery time estimate |
| nomor_resi | string | Shipping receipt number |
| status_pengiriman | string | Shipping status |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pengiriman pesanan berhasil diperbarui",
  "data": {...} // Updated order shipping resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/order-shipping/{id} - Delete order shipping

Delete a specific order shipping record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order shipping ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pengiriman pesanan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Payment Method Resource Endpoints

**Base Endpoint**: `/api/payment-methods`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for payment method management.

#### GET /api/payment-methods - List all payment methods

List all payment methods with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter payment methods |
| tipe_pembayaran | string | No | Filter by payment type |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data metode pembayaran berhasil diambil",
  "data": [...], // Array of payment method resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/payment-methods - Create new payment method

Create a new payment method record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| nama_pembayaran | string | Yes | Payment method name (max: 255 characters) |
| tipe_pembayaran | string | Yes | Payment type (TRANSFER_BANK/E_WALLET/VIRTUAL_ACCOUNT/CREDIT_CARD/COD/QRIS) |
| provider_pembayaran | string | Yes | Payment provider name (max: 255 characters) |
| deskripsi_pembayaran | string | No | Payment method description |
| biaya_transaksi | number | No | Transaction fee |
| status_pembayaran | string | No | Payment method status (AKTIF/TIDAK_AKTIF) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Metode pembayaran berhasil dibuat",
  "data": {...} // Created payment method resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/payment-methods/{id} - Get specific payment method

Retrieve a specific payment method's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment method ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data metode pembayaran berhasil diambil",
  "data": {...} // Payment method resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/payment-methods/{id} - Update payment method

Update payment method information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment method ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| nama_pembayaran | string | Payment method name (max: 255 characters) |
| tipe_pembayaran | string | Payment type (TRANSFER_BANK/E_WALLET/VIRTUAL_ACCOUNT/CREDIT_CARD/COD/QRIS) |
| provider_pembayaran | string | Payment provider name (max: 255 characters) |
| deskripsi_pembayaran | string | Payment method description |
| biaya_transaksi | number | Transaction fee |
| status_pembayaran | string | Payment method status (AKTIF/TIDAK_AKTIF) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Metode pembayaran berhasil diperbarui",
  "data": {...} // Updated payment method resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/payment-methods/{id} - Delete payment method

Delete a specific payment method record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment method ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Metode pembayaran berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Payment Transaction Resource Endpoints

**Base Endpoint**: `/api/payment-transactions`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for payment transaction management.

#### GET /api/payment-transactions - List all payment transactions

List all payment transactions with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| order_id | integer | No | Filter by specific order |
| payment_method_id | integer | No | Filter by specific payment method |
| status_transaksi | string | No | Filter by transaction status |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data transaksi pembayaran berhasil diambil",
  "data": [...], // Array of payment transaction resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/payment-transactions - Create new payment transaction

Create a new payment transaction record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_pesanan | integer | Yes | Order ID (must exist in pesanan table) |
| id_metode_pembayaran | integer | Yes | Payment method ID (must exist in metode_pembayaran table) |
| referensi_id | string | Yes | Reference ID (must be unique) |
| jumlah_pembayaran | number | Yes | Payment amount |
| status_transaksi | string | No | Transaction status (default: PENDING) |
| tanggal_transaksi | datetime | No | Transaction date (default: now) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Transaksi pembayaran berhasil dibuat",
  "data": {...} // Created payment transaction resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/payment-transactions/{id} - Get specific payment transaction

Retrieve a specific payment transaction's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment transaction ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data transaksi pembayaran berhasil diambil",
  "data": {...} // Payment transaction resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/payment-transactions/{id} - Update payment transaction

Update payment transaction information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment transaction ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_pesanan | integer | Order ID (must exist in pesanan table) |
| id_metode_pembayaran | integer | Payment method ID (must exist in metode_pembayaran table) |
| referensi_id | string | Reference ID (must be unique) |
| jumlah_pembayaran | number | Payment amount |
| status_transaksi | string | Transaction status |
| tanggal_transaksi | datetime | Transaction date |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Transaksi pembayaran berhasil diperbarui",
  "data": {...} // Updated payment transaction resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/payment-transactions/{id} - Delete payment transaction

Delete a specific payment transaction record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment transaction ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Transaksi pembayaran berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Payment Log Resource Endpoints

**Base Endpoint**: `/api/payment-logs`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for payment log management.

#### GET /api/payment-logs - List all payment logs

List all payment logs with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| transaction_id | integer | No | Filter by specific transaction |
| tipe_log | string | No | Filter by log type |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data log pembayaran berhasil diambil",
  "data": [...], // Array of payment log resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/payment-logs - Create new payment log

Create a new payment log record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_transaksi_pembayaran | integer | Yes | Payment transaction ID (must exist in transaksi_pembayaran table) |
| tipe_log | string | Yes | Log type (REQUEST/RESPONSE/CALLBACK/ERROR) |
| konten_log | string | Yes | Log content |
| timestamp_log | datetime | No | Log timestamp (default: now) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Log pembayaran berhasil dibuat",
  "data": {...} // Created payment log resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/payment-logs/{id} - Get specific payment log

Retrieve a specific payment log's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment log ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data log pembayaran berhasil diambil",
  "data": {...} // Payment log resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/payment-logs/{id} - Update payment log

Update payment log information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment log ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_transaksi_pembayaran | integer | Payment transaction ID (must exist in transaksi_pembayaran table) |
| tipe_log | string | Log type (REQUEST/RESPONSE/CALLBACK/ERROR) |
| konten_log | string | Log content |
| timestamp_log | datetime | Log timestamp |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Log pembayaran berhasil diperbarui",
  "data": {...} // Updated payment log resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/payment-logs/{id} - Delete payment log

Delete a specific payment log record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment log ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Log pembayaran berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Review & Feedback APIs

### Product Review Resource Endpoints

**Base Endpoint**: `/api/product-reviews`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for product review management.

#### GET /api/product-reviews - List all product reviews

List all product reviews with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| product_id | integer | No | Filter by specific product |
| seller_id | integer | No | Filter by specific seller |
| buyer_id | integer | No | Filter by specific buyer |
| rating | integer | No | Filter by rating value |
| sort_by | string | No | Sort by field (created_at/updated_at/rating) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data ulasan produk berhasil diambil",
  "data": [...], // Array of review resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/product-reviews - Create new product review

Create a new product review.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_produk | integer | Yes | Product ID (must exist in tb_produk table) |
| id_harga_varian | integer | No | Variant price ID (must exist in harga_varian_produk table) |
| id_pembeli | integer | Yes | Buyer ID (must exist in users table) |
| rating | integer | Yes | Rating value (1-5) |
| komentar | string | No | Review comment |
| judul | string | No | Review title |
| status_ulasan | string | No | Review status (default: AKTIF) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Ulasan produk berhasil dibuat",
  "data": {...} // Created review resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/product-reviews/{id} - Get specific product review

Retrieve a specific product review's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data ulasan produk berhasil diambil",
  "data": {...} // Review resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/product-reviews/{id} - Update product review

Update product review information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_produk | integer | Product ID (must exist in tb_produk table) |
| id_harga_varian | integer | Variant price ID (must exist in harga_varian_produk table) |
| id_pembeli | integer | Buyer ID (must exist in users table) |
| rating | integer | Rating value (1-5) |
| komentar | string | Review comment |
| judul | string | Review title |
| status_ulasan | string | Review status |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Ulasan produk berhasil diperbarui",
  "data": {...} // Updated review resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/product-reviews/{id} - Delete product review

Delete a specific product review record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Ulasan produk berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Review Media Resource Endpoints

**Base Endpoint**: `/api/review-media`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for review media management.

#### GET /api/review-media - List all review media

List all review media with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| review_id | integer | No | Filter by specific review |
| tipe_media | string | No | Filter by media type |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data media ulasan berhasil diambil",
  "data": [...], // Array of review media resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/review-media - Create new review media

Create a new review media record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_review | integer | Yes | Review ID (must exist in ulasan_produk table) |
| tipe_media | string | Yes | Media type (GAMBAR/VIDEO) |
| url_media | string | Yes | Media URL (max: 2048 characters) |
| urutan | integer | No | Display order (default: 0) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Media ulasan berhasil dibuat",
  "data": {...} // Created review media resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/review-media/{id} - Get specific review media

Retrieve a specific review media's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review media ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data media ulasan berhasil diambil",
  "data": {...} // Review media resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/review-media/{id} - Update review media

Update review media information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review media ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_review | integer | Review ID (must exist in ulasan_produk table) |
| tipe_media | string | Media type (GAMBAR/VIDEO) |
| url_media | string | Media URL (max: 2048 characters) |
| urutan | integer | Display order |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Media ulasan berhasil diperbarui",
  "data": {...} // Updated review media resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/review-media/{id} - Delete review media

Delete a specific review media record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review media ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Media ulasan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Review Vote Resource Endpoints

**Base Endpoint**: `/api/review-votes`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for review vote management.

#### GET /api/review-votes - List all review votes

List all review votes with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| review_id | integer | No | Filter by specific review |
| user_id | integer | No | Filter by specific user |
| tipe_vote | string | No | Filter by vote type |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data suara ulasan berhasil diambil",
  "data": [...], // Array of review vote resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/review-votes - Create new review vote

Create a new review vote record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_review | integer | Yes | Review ID (must exist in ulasan_produk table) |
| id_user | integer | Yes | User ID (must exist in users table) |
| tipe_vote | string | Yes | Vote type (SUKA/TIDAK_SUKA) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Suara ulasan berhasil dibuat",
  "data": {...} // Created review vote resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/review-votes/{id} - Get specific review vote

Retrieve a specific review vote's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review vote ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data suara ulasan berhasil diambil",
  "data": {...} // Review vote resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/review-votes/{id} - Update review vote

Update review vote information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review vote ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_review | integer | Review ID (must exist in ulasan_produk table) |
| id_user | integer | User ID (must exist in users table) |
| tipe_vote | string | Vote type (SUKA/TIDAK_SUKA) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Suara ulasan berhasil diperbarui",
  "data": {...} // Updated review vote resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/review-votes/{id} - Delete review vote

Delete a specific review vote record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review vote ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Suara ulasan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Notification & Activity APIs

### Notification Resource Endpoints

**Base Endpoint**: `/api/notifications`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for user notification management.

#### GET /api/notifications - List all notifications

List all notifications with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| user_id | integer | No | Filter by specific user |
| tipe_notifikasi | string | No | Filter by notification type |
| status_dibaca | boolean | No | Filter by read status |
| sort_by | string | No | Sort by field (created_at/updated_at) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data notifikasi berhasil diambil",
  "data": [...], // Array of notification resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/notifications - Create new notification

Create a new notification record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_user | integer | Yes | User ID (must exist in users table) |
| tipe_notifikasi | string | Yes | Notification type (PESANAN/PEMBAYARAN/PENGIRIMAN/ULASAN/SYSTEM/CHAT) |
| judul_notifikasi | string | Yes | Notification title (max: 255 characters) |
| isi_notifikasi | string | Yes | Notification content |
| link_notifikasi | string | No | Notification link |
| status_dibaca | boolean | No | Whether notification has been read (default: false) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Notifikasi berhasil dibuat",
  "data": {...} // Created notification resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/notifications/{id} - Get specific notification

Retrieve a specific notification's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Notification ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data notifikasi berhasil diambil",
  "data": {...} // Notification resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/notifications/{id} - Update notification

Update notification information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Notification ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| tipe_notifikasi | string | Notification type (PESANAN/PEMBAYARAN/PENGIRIMAN/ULASAN/SYSTEM/CHAT) |
| judul_notifikasi | string | Notification title (max: 255 characters) |
| isi_notifikasi | string | Notification content |
| link_notifikasi | string | Notification link |
| status_dibaca | boolean | Whether notification has been read |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Notifikasi berhasil diperbarui",
  "data": {...} // Updated notification resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/notifications/{id} - Delete notification

Delete a specific notification record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Notification ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Notifikasi berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### User Activity Resource Endpoints

**Base Endpoint**: `/api/activities`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for user activity management.

#### GET /api/activities - List all activities

List all activities with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| user_id | integer | No | Filter by specific user |
| tipe_aktivitas | string | No | Filter by activity type |
| tanggal_mulai | date | No | Filter by start date |
| tanggal_selesai | date | No | Filter by end date |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data aktivitas berhasil diambil",
  "data": [...], // Array of activity resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/activities - Create new activity

Create a new activity record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_user | integer | Yes | User ID (must exist in users table) |
| sesi_id | string | No | Session ID (max: 255 characters) |
| tipe_aktivitas | string | Yes | Activity type (LOGIN/LOGOUT/VIEW_PRODUK/CARI_PRODUK/TAMBAH_KERANJANG/CHECKOUT/PAYMENT/REVIEW/CHAT) |
| deskripsi_aktivitas | string | No | Activity description |
| ip_address | string | No | IP address of the activity |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Aktivitas berhasil dibuat",
  "data": {...} // Created activity resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/activities/{id} - Get specific activity

Retrieve a specific activity's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Activity ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data aktivitas berhasil diambil",
  "data": {...} // Activity resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/activities/{id} - Update activity

Update activity information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Activity ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| sesi_id | string | Session ID (max: 255 characters) |
| tipe_aktivitas | string | Activity type (LOGIN/LOGOUT/VIEW_PRODUK/CARI_PRODUK/TAMBAH_KERANJANG/CHECKOUT/PAYMENT/REVIEW/CHAT) |
| deskripsi_aktivitas | string | Activity description |
| ip_address | string | IP address of the activity |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Aktivitas berhasil diperbarui",
  "data": {...} // Updated activity resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/activities/{id} - Delete activity

Delete a specific activity record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Activity ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Aktivitas berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Search History Resource Endpoints

**Base Endpoint**: `/api/search-history`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for search history management.

#### GET /api/search-history - List all search histories

List all search histories with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| user_id | integer | No | Filter by specific user |
| kata_pencarian | string | No | Filter by search term |
| tanggal_mulai | date | No | Filter by start date |
| tanggal_selesai | date | No | Filter by end date |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data riwayat pencarian berhasil diambil",
  "data": [...], // Array of search history resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/search-history - Create new search history

Create a new search history record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_user | integer | Yes | User ID (must exist in users table) |
| kata_pencarian | string | Yes | Search term (max: 255 characters) |
| jumlah_hasil | integer | Yes | Number of search results (min: 0) |
| tipe_pencarian | string | No | Search type (PRODUK/PELANGGAN/PELANGGAN_LAINNYA) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Riwayat pencarian berhasil dibuat",
  "data": {...} // Created search history resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/search-history/{id} - Get specific search history

Retrieve a specific search history's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Search history ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data riwayat pencarian berhasil diambil",
  "data": {...} // Search history resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/search-history/{id} - Update search history

Update search history information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Search history ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| kata_pencarian | string | Search term (max: 255 characters) |
| jumlah_hasil | integer | Number of search results (min: 0) |
| tipe_pencarian | string | Search type (PRODUK/PELANGGAN/PELANGGAN_LAINNYA) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Riwayat pencarian berhasil diperbarui",
  "data": {...} // Updated search history resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/search-history/{id} - Delete search history

Delete a specific search history record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Search history ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Riwayat pencarian berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Admin & Reporting APIs

### Admin User Resource Endpoints

**Base Endpoint**: `/api/admin-users`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for admin user management.

#### GET /api/admin-users - List all admin users

List all admin users with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter admin users |
| role_admin | string | No | Filter by admin role |
| sort_by | string | No | Sort by field (created_at/updated_at) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pengguna admin berhasil diambil",
  "data": [...], // Array of admin user resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/admin-users - Create new admin user

Create a new admin user record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_user | integer | Yes | User ID (must exist in users table) |
| role_admin | string | Yes | Admin role (SUPER_ADMIN/ADMIN_CONTENT/ADMIN_FINANCE/ADMIN_CUSTOMER/ADMIN_LOGISTIC) |
| permissions | json | No | Admin permissions |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Pengguna admin berhasil dibuat",
  "data": {...} // Created admin user resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/admin-users/{id} - Get specific admin user

Retrieve a specific admin user's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Admin user ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pengguna admin berhasil diambil",
  "data": {...} // Admin user resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/admin-users/{id} - Update admin user

Update admin user information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Admin user ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| role_admin | string | Admin role (SUPER_ADMIN/ADMIN_CONTENT/ADMIN_FINANCE/ADMIN_CUSTOMER/ADMIN_LOGISTIC) |
| permissions | json | Admin permissions |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pengguna admin berhasil diperbarui",
  "data": {...} // Updated admin user resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/admin-users/{id} - Delete admin user

Delete a specific admin user record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Admin user ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pengguna admin berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Seller Report Resource Endpoints

**Base Endpoint**: `/api/seller-reports`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for seller report management.

#### GET /api/seller-reports - List all seller reports

List all seller reports with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| seller_id | integer | No | Filter by specific seller |
| tipe_laporan | string | No | Filter by report type |
| periode_laporan | date | No | Filter by report period |
| sort_by | string | No | Sort by field (created_at/updated_at/periode_laporan) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data laporan penjual berhasil diambil",
  "data": [...], // Array of seller report resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/seller-reports - Create new seller report

Create a new seller report record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_seller | integer | Yes | Seller ID (must exist in penjual table) |
| tipe_laporan | string | Yes | Report type (HARIAN/MINGGUAN/BULANAN/TAHUNAN) |
| periode_laporan | date | Yes | Report period |
| data_laporan | json | No | Report data |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Laporan penjual berhasil dibuat",
  "data": {...} // Created seller report resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/seller-reports/{id} - Get specific seller report

Retrieve a specific seller report's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Seller report ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data laporan penjual berhasil diambil",
  "data": {...} // Seller report resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/seller-reports/{id} - Update seller report

Update seller report information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Seller report ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_seller | integer | Seller ID (must exist in penjual table) |
| tipe_laporan | string | Report type (HARIAN/MINGGUAN/BULANAN/TAHUNAN) |
| periode_laporan | date | Report period |
| data_laporan | json | Report data |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Laporan penjual berhasil diperbarui",
  "data": {...} // Updated seller report resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/seller-reports/{id} - Delete seller report

Delete a specific seller report record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Seller report ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Laporan penjual berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Sales Report Resource Endpoints

**Base Endpoint**: `/api/sales-reports`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for sales report management.

#### GET /api/sales-reports - List all sales reports

List all sales reports with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| tipe_laporan | string | No | Filter by report type |
| periode_laporan | date | No | Filter by report period |
| sort_by | string | No | Sort by field (created_at/updated_at/periode_laporan) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data laporan penjualan berhasil diambil",
  "data": [...], // Array of sales report resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/sales-reports - Create new sales report

Create a new sales report record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| tipe_laporan | string | Yes | Report type (GLOBAL/KATEGORI/PROVINSI/METODE_PEMBAYARAN) |
| periode_laporan | date | Yes | Report period |
| data_laporan | json | No | Report data |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Laporan penjualan berhasil dibuat",
  "data": {...} // Created sales report resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/sales-reports/{id} - Get specific sales report

Retrieve a specific sales report's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Sales report ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data laporan penjualan berhasil diambil",
  "data": {...} // Sales report resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/sales-reports/{id} - Update sales report

Update sales report information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Sales report ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| tipe_laporan | string | Report type (GLOBAL/KATEGORI/PROVINSI/METODE_PEMBAYARAN) |
| periode_laporan | date | Report period |
| data_laporan | json | Report data |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Laporan penjualan berhasil diperbarui",
  "data": {...} // Updated sales report resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/sales-reports/{id} - Delete sales report

Delete a specific sales report record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Sales report ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Laporan penjualan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Chat System APIs

### Chat Conversation Resource Endpoints

**Base Endpoint**: `/api/chat-conversations`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for chat conversation management.

#### GET /api/chat-conversations - List all chat conversations

List all chat conversations with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| tipe | string | No | Filter by conversation type |
| owner_user_id | integer | No | Filter by conversation owner |
| sort_by | string | No | Sort by field (created_at/updated_at) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data percakapan chat berhasil diambil",
  "data": [...], // Array of chat conversation resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/chat-conversations - Create new chat conversation

Create a new chat conversation record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| tipe | string | Yes | Conversation type (PRIVATE/GROUP/ORDER_SUPPORT,PRODUCT_SUPPORT,SYSTEM) |
| judul | string | No | Conversation title (max: 512 characters) |
| owner_user_id | integer | No | Owner user ID (must exist in users table) |
| deskripsi | string | No | Conversation description |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Percakapan chat berhasil dibuat",
  "data": {...} // Created chat conversation resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/chat-conversations/{id} - Get specific chat conversation

Retrieve a specific chat conversation's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat conversation ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data percakapan chat berhasil diambil",
  "data": {...} // Chat conversation resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/chat-conversations/{id} - Update chat conversation

Update chat conversation information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat conversation ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| tipe | string | Conversation type (PRIVATE/GROUP/ORDER_SUPPORT,PRODUCT_SUPPORT,SYSTEM) |
| judul | string | Conversation title (max: 512 characters) |
| owner_user_id | integer | Owner user ID (must exist in users table) |
| deskripsi | string | Conversation description |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Percakapan chat berhasil diperbarui",
  "data": {...} // Updated chat conversation resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/chat-conversations/{id} - Delete chat conversation

Delete a specific chat conversation record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat conversation ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Percakapan chat berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Chat Participant Resource Endpoints

**Base Endpoint**: `/api/chat-participants`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for chat participant management.

#### GET /api/chat-participants - List all chat participants

List all chat participants with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| percakapan_id | integer | No | Filter by specific conversation |
| user_id | integer | No | Filter by specific user |
| shop_profile_id | integer | No | Filter by specific shop profile |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data peserta chat berhasil diambil",
  "data": [...], // Array of chat participant resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/chat-participants - Create new chat participant

Create a new chat participant record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| percakapan_id | integer | Yes | Conversation ID (must exist in chat_percakapan table) |
| user_id | integer | No | User ID (must exist in users table) |
| shop_profile_id | integer | No | Shop profile ID (must exist in penjual table) |
| status_partisipan | string | No | Participant status (AKTIF/BLOCKED) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Peserta chat berhasil dibuat",
  "data": {...} // Created chat participant resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/chat-participants/{id} - Get specific chat participant

Retrieve a specific chat participant's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat participant ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data peserta chat berhasil diambil",
  "data": {...} // Chat participant resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/chat-participants/{id} - Update chat participant

Update chat participant information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat participant ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| percakapan_id | integer | Conversation ID (must exist in chat_percakapan table) |
| user_id | integer | User ID (must exist in users table) |
| shop_profile_id | integer | Shop profile ID (must exist in penjual table) |
| status_partisipan | string | Participant status (AKTIF/BLOCKED) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Peserta chat berhasil diperbarui",
  "data": {...} // Updated chat participant resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/chat-participants/{id} - Delete chat participant

Delete a specific chat participant record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat participant ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Peserta chat berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Chat Message Resource Endpoints

**Base Endpoint**: `/api/chat-messages`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for chat message management.

#### GET /api/chat-messages - List all chat messages

List all chat messages with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| percakapan_id | integer | No | Filter by specific conversation |
| pengirim_user_id | integer | No | Filter by specific sender |
| pengirim_shop_profile_id | integer | No | Filter by specific shop sender |
| sort_by | string | No | Sort by field (created_at/updated_at) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pesan chat berhasil diambil",
  "data": [...], // Array of chat message resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/chat-messages - Create new chat message

Create a new chat message record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| percakapan_id | integer | Yes | Conversation ID (must exist in chat_percakapan table) |
| pengirim_user_id | integer | No | Sender user ID (must exist in users table) |
| pengirim_shop_profile_id | integer | No | Sender shop profile ID (must exist in penjual table) |
| isi_pesan | string | Yes | Message content |
| tipe_pesan | string | No | Message type (TEXT/IMAGE/AUDIO/VIDEO/DOCUMENT/FILE/LINK) |
| status_pesan | string | No | Message status (TERKIRIM/DITERIMA/DIBACA/GAGAL) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Pesan chat berhasil dibuat",
  "data": {...} // Created chat message resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/chat-messages/{id} - Get specific chat message

Retrieve a specific chat message's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat message ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pesan chat berhasil diambil",
  "data": {...} // Chat message resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/chat-messages/{id} - Update chat message

Update chat message information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat message ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| percakapan_id | integer | Conversation ID (must exist in chat_percakapan table) |
| pengirim_user_id | integer | Sender user ID (must exist in users table) |
| pengirim_shop_profile_id | integer | Sender shop profile ID (must exist in penjual table) |
| isi_pesan | string | Message content |
| tipe_pesan | string | Message type (TEXT/IMAGE/AUDIO/VIDEO/DOCUMENT/FILE/LINK) |
| status_pesan | string | Message status (TERKIRIM/DITERIMA/DIBACA/GAGAL) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pesan chat berhasil diperbarui",
  "data": {...} // Updated chat message resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/chat-messages/{id} - Delete chat message

Delete a specific chat message record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat message ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pesan chat berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Message Status Resource Endpoints

**Base Endpoint**: `/api/message-statuses`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for message status management.

#### GET /api/message-statuses - List all message statuses

List all message statuses with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| pesan_id | integer | No | Filter by specific message |
| user_id | integer | No | Filter by specific user |
| status | string | No | Filter by status |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data status pesan berhasil diambil",
  "data": [...], // Array of message status resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/message-statuses - Create new message status

Create a new message status record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| pesan_id | integer | Yes | Message ID (must exist in chat_pesan_chat table) |
| user_id | integer | Yes | User ID (must exist in users table) |
| status | string | Yes | Status (TERKIRIM/DITERIMA/DIBACA/GAGAL) |
| timestamp_status | datetime | No | Status timestamp (default: now) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Status pesan berhasil dibuat",
  "data": {...} // Created message status resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/message-statuses/{id} - Get specific message status

Retrieve a specific message status's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message status ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data status pesan berhasil diambil",
  "data": {...} // Message status resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/message-statuses/{id} - Update message status

Update message status information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message status ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| pesan_id | integer | Message ID (must exist in chat_pesan_chat table) |
| user_id | integer | User ID (must exist in users table) |
| status | string | Status (TERKIRIM/DITERIMA/DIBACA/GAGAL) |
| timestamp_status | datetime | Status timestamp |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Status pesan berhasil diperbarui",
  "data": {...} // Updated message status resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/message-statuses/{id} - Delete message status

Delete a specific message status record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message status ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Status pesan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Message Reaction Resource Endpoints

**Base Endpoint**: `/api/message-reactions`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for message reaction management.

#### GET /api/message-reactions - List all message reactions

List all message reactions with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| pesan_id | integer | No | Filter by specific message |
| user_id | integer | No | Filter by specific user |
| reaksi | string | No | Filter by reaction |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data reaksi pesan berhasil diambil",
  "data": [...], // Array of message reaction resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/message-reactions - Create new message reaction

Create a new message reaction record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| pesan_id | integer | Yes | Message ID (must exist in chat_pesan_chat table) |
| user_id | integer | Yes | User ID (must exist in users table) |
| reaksi | string | Yes | Reaction (max: 10 characters) |
| timestamp_reaksi | datetime | No | Reaction timestamp (default: now) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Reaksi pesan berhasil dibuat",
  "data": {...} // Created message reaction resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/message-reactions/{id} - Get specific message reaction

Retrieve a specific message reaction's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message reaction ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data reaksi pesan berhasil diambil",
  "data": {...} // Message reaction resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/message-reactions/{id} - Update message reaction

Update message reaction information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message reaction ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| pesan_id | integer | Message ID (must exist in chat_pesan_chat table) |
| user_id | integer | User ID (must exist in users table) |
| reaksi | string | Reaction (max: 10 characters) |
| timestamp_reaksi | datetime | Reaction timestamp |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Reaksi pesan berhasil diperbarui",
  "data": {...} // Updated message reaction resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/message-reactions/{id} - Delete message reaction

Delete a specific message reaction record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message reaction ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Reaksi pesan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Message Edit Resource Endpoints

**Base Endpoint**: `/api/message-edits`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for message edit management.

#### GET /api/message-edits - List all message edits

List all message edits with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| pesan_id | integer | No | Filter by specific message |
| editor_id | integer | No | Filter by specific editor |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data edit pesan berhasil diambil",
  "data": [...], // Array of message edit resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/message-edits - Create new message edit

Create a new message edit record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| pesan_id | integer | Yes | Message ID (must exist in chat_pesan_chat table) |
| editor_id | integer | Yes | Editor ID (must exist in users table) |
| isi_sebelumnya | string | Yes | Previous content (max: 1000 characters) |
| isi_baru | string | Yes | New content (max: 1000 characters) |
| timestamp_edit | datetime | No | Edit timestamp (default: now) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Edit pesan berhasil dibuat",
  "data": {...} // Created message edit resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/message-edits/{id} - Get specific message edit

Retrieve a specific message edit's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message edit ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data edit pesan berhasil diambil",
  "data": {...} // Message edit resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/message-edits/{id} - Update message edit

Update message edit information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message edit ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| pesan_id | integer | Message ID (must exist in chat_pesan_chat table) |
| editor_id | integer | Editor ID (must exist in users table) |
| isi_sebelumnya | string | Previous content (max: 1000 characters) |
| isi_baru | string | New content (max: 1000 characters) |
| timestamp_edit | datetime | Edit timestamp |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Edit pesan berhasil diperbarui",
  "data": {...} // Updated message edit resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/message-edits/{id} - Delete message edit

Delete a specific message edit record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message edit ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Edit pesan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Message Attachment Resource Endpoints

**Base Endpoint**: `/api/message-attachments`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for message attachment management.

#### GET /api/message-attachments - List all message attachments

List all message attachments with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| pesan_id | integer | No | Filter by specific message |
| tipe_lampiran | string | No | Filter by attachment type |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data lampiran pesan berhasil diambil",
  "data": [...], // Array of message attachment resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/message-attachments - Create new message attachment

Create a new message attachment record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| pesan_id | integer | Yes | Message ID (must exist in chat_pesan_chat table) |
| tipe_lampiran | string | Yes | Attachment type (IMAGE/VIDEO/AUDIO/DOCUMENT/FILE) |
| url_lampiran | string | Yes | Attachment URL (max: 2048 characters) |
| ukuran_lampiran | integer | No | Attachment size in bytes |
| nama_file | string | No | File name (max: 255 characters) |
| mime_type | string | No | MIME type (max: 255 characters) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Lampiran pesan berhasil dibuat",
  "data": {...} // Created message attachment resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/message-attachments/{id} - Get specific message attachment

Retrieve a specific message attachment's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message attachment ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data lampiran pesan berhasil diambil",
  "data": {...} // Message attachment resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/message-attachments/{id} - Update message attachment

Update message attachment information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message attachment ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| pesan_id | integer | Message ID (must exist in chat_pesan_chat table) |
| tipe_lampiran | string | Attachment type (IMAGE/VIDEO/AUDIO/DOCUMENT/FILE) |
| url_lampiran | string | Attachment URL (max: 2048 characters) |
| ukuran_lampiran | integer | Attachment size in bytes |
| nama_file | string | File name (max: 255 characters) |
| mime_type | string | MIME type (max: 255 characters) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Lampiran pesan berhasil diperbarui",
  "data": {...} // Updated message attachment resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/message-attachments/{id} - Delete message attachment

Delete a specific message attachment record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message attachment ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Lampiran pesan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Chat Product Reference Resource Endpoints

**Base Endpoint**: `/api/chat-product-references`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for chat product reference management.

#### GET /api/chat-product-references - List all chat product references

List all chat product references with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| pesan_id | integer | No | Filter by specific message |
| produk_id | integer | No | Filter by specific product |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data referensi produk chat berhasil diambil",
  "data": [...], // Array of chat product reference resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/chat-product-references - Create new chat product reference

Create a new chat product reference record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| pesan_id | integer | Yes | Message ID (must exist in chat_pesan_chat table) |
| produk_id | integer | Yes | Product ID (must exist in produk table) |
| posisi_mulai | integer | No | Start position in message |
| posisi_akhir | integer | No | End position in message |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Referensi produk chat berhasil dibuat",
  "data": {...} // Created chat product reference resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/chat-product-references/{id} - Get specific chat product reference

Retrieve a specific chat product reference's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat product reference ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data referensi produk chat berhasil diambil",
  "data": {...} // Chat product reference resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/chat-product-references/{id} - Update chat product reference

Update chat product reference information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat product reference ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| pesan_id | integer | Message ID (must exist in chat_pesan_chat table) |
| produk_id | integer | Product ID (must exist in produk table) |
| posisi_mulai | integer | Start position in message |
| posisi_akhir | integer | End position in message |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Referensi produk chat berhasil diperbarui",
  "data": {...} // Updated chat product reference resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/chat-product-references/{id} - Delete chat product reference

Delete a specific chat product reference record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat product reference ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Referensi produk chat berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Chat Order Reference Resource Endpoints

**Base Endpoint**: `/api/chat-order-references`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for chat order reference management.

#### GET /api/chat-order-references - List all chat order references

List all chat order references with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| pesan_id | integer | No | Filter by specific message |
| order_id | integer | No | Filter by specific order |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data referensi order chat berhasil diambil",
  "data": [...], // Array of chat order reference resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/chat-order-references - Create new chat order reference

Create a new chat order reference record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| pesan_id | integer | Yes | Message ID (must exist in chat_pesan_chat table) |
| order_id | integer | Yes | Order ID (must exist in orders table) |
| posisi_mulai | integer | No | Start position in message |
| posisi_akhir | integer | No | End position in message |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Referensi order chat berhasil dibuat",
  "data": {...} // Created chat order reference resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/chat-order-references/{id} - Get specific chat order reference

Retrieve a specific chat order reference's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat order reference ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data referensi order chat berhasil diambil",
  "data": {...} // Chat order reference resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/chat-order-references/{id} - Update chat order reference

Update chat order reference information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat order reference ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| pesan_id | integer | Message ID (must exist in chat_pesan_chat table) |
| order_id | integer | Order ID (must exist in orders table) |
| posisi_mulai | integer | Start position in message |
| posisi_akhir | integer | End position in message |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Referensi order chat berhasil diperbarui",
  "data": {...} // Updated chat order reference resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/chat-order-references/{id} - Delete chat order reference

Delete a specific chat order reference record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat order reference ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Referensi order chat berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Chat Report Resource Endpoints

**Base Endpoint**: `/api/chat-reports`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for chat report management.

#### GET /api/chat-reports - List all chat reports

List all chat reports with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| percakapan_id | integer | No | Filter by specific conversation |
| reporter_id | integer | No | Filter by specific reporter |
| tipe_laporan | string | No | Filter by report type |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data laporan chat berhasil diambil",
  "data": [...], // Array of chat report resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/chat-reports - Create new chat report

Create a new chat report record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| percakapan_id | integer | Yes | Conversation ID (must exist in chat_percakapan table) |
| reporter_id | integer | Yes | Reporter ID (must exist in users table) |
| alasan | string | Yes | Reason for report (max: 500 characters) |
| tipe_laporan | string | No | Report type (SPAM/HARASSMENT/INAPPROPRIATE_CONTENT/OTHER) |
| status_laporan | string | No | Report status (PENDING/RESOLVED/DISMISSED) |
| tanggapan_admin | string | No | Admin response |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Laporan chat berhasil dibuat",
  "data": {...} // Created chat report resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/chat-reports/{id} - Get specific chat report

Retrieve a specific chat report's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat report ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data laporan chat berhasil diambil",
  "data": {...} // Chat report resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/chat-reports/{id} - Update chat report

Update chat report information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat report ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| percakapan_id | integer | Conversation ID (must exist in chat_percakapan table) |
| reporter_id | integer | Reporter ID (must exist in users table) |
| alasan | string | Reason for report (max: 500 characters) |
| tipe_laporan | string | Report type (SPAM/HARASSMENT/INAPPROPRIATE_CONTENT/OTHER) |
| status_laporan | string | Report status (PENDING/RESOLVED/DISMISSED) |
| tanggapan_admin | string | Admin response |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Laporan chat berhasil diperbarui",
  "data": {...} // Updated chat report resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/chat-reports/{id} - Delete chat report

Delete a specific chat report record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat report ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Laporan chat berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## System Settings API

### System Setting Resource Endpoints

**Base Endpoint**: `/api/system-settings`  
**Authentication**: Bearer token (Laravel Sanctum)

Provides full CRUD operations for system setting management.

#### GET /api/system-settings - List all system settings

List all system settings with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter settings |
| kunci_pengaturan | string | No | Filter by setting key |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pengaturan sistem berhasil diambil",
  "data": [...], // Array of system setting resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/system-settings - Create new system setting

Create a new system setting record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| kunci_pengaturan | string | Yes | Setting key (max: 255 characters, must be unique) |
| nilai_pengaturan | string | Yes | Setting value |
| tipe_pengaturan | string | Yes | Setting type (STRING/NUMBER/BOOLEAN/JSON) |
| deskripsi_pengaturan | string | No | Setting description |
| grup_pengaturan | string | No | Setting group |
| dapat_diubah | boolean | No | Whether setting can be modified (default: true) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Pengaturan sistem berhasil dibuat",
  "data": {...} // Created system setting resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/system-settings/{id} - Get specific system setting

Retrieve a specific system setting's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | System setting ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pengaturan sistem berhasil diambil",
  "data": {...} // System setting resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/system-settings/{id} - Update system setting

Update system setting information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | System setting ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| kunci_pengaturan | string | Setting key (max: 255 characters, must be unique) |
| nilai_pengaturan | string | Setting value |
| tipe_pengaturan | string | Setting type (STRING/NUMBER/BOOLEAN/JSON) |
| deskripsi_pengaturan | string | Setting description |
| grup_pengaturan | string | Setting group |
| dapat_diubah | boolean | Whether setting can be modified |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pengaturan sistem berhasil diperbarui",
  "data": {...} // Updated system setting resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/system-settings/{id} - Delete system setting

Delete a specific system setting record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | System setting ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pengaturan sistem berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Response Format

All API responses follow a consistent format:

```json
{
  "success": true/false,
  "message": "descriptive message about the operation",
  "data": {...}, // optional data field
  "errors": {...}, // optional errors field for validation errors
  "pagination": {...} // optional pagination information
}
```

## Error Codes

| HTTP Code | Description |
|-----------|-------------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request - Invalid input data |
| 401 | Unauthorized - Invalid or missing token |
| 403 | Forbidden - Insufficient permissions |
| 404 | Not Found - Resource does not exist |
| 422 | Unprocessable Entity - Validation failed |
| 500 | Internal Server Error - Unexpected server error |

```json
{
  "success": true/false,
  "message": "descriptive message about the operation",
  "data": {...}, // optional data field
  "errors": {...}, // optional errors field for validation errors
  "pagination": {...} // optional pagination information
}
```

## Error Codes

| HTTP Code | Description |
|-----------|-------------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request - Invalid input data |
| 401 | Unauthorized - Invalid or missing token |
| 403 | Forbidden - Insufficient permissions |
| 404 | Not Found - Resource does not exist |
| 422 | Unprocessable Entity - Validation failed |
| 500 | Internal Server Error - Unexpected server error |## Seller Management API

### Seller Resource Endpoints

**Base Endpoint**: `/api/sellers`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for seller management.

#### GET /api/sellers - List all sellers

List all sellers with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter sellers by name or shop name |
| status | string | No | Filter by seller status (AKTIF/TIDAK_AKTIF/DIBLOKIR/SUSPEND) |
| sort_by | string | No | Sort by field (created_at/updated_at/nama_toko) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data penjual berhasil diambil",
  "data": [...], // Array of seller resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/sellers - Create new seller

Create a new seller account.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_user | integer | Yes | User ID (must exist in users table) |
| nama_toko | string | Yes | Shop name (max: 255 characters) |
| deskripsi_toko | string | No | Shop description |
| alamat_toko | string | No | Shop address |
| nomor_telepon_toko | string | No | Shop contact number |
| status_toko | string | No | Seller status (AKTIF/TIDAK_AKTIF/DIBLOKIR/SUSPEND) |
| rating | number | No | Seller rating (0.00 to 5.00) |
| jumlah_transaksi | integer | No | Number of completed transactions |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Penjual berhasil dibuat",
  "data": {...} // Created seller resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/sellers/{id} - Get specific seller

Retrieve a specific seller's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Seller ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data penjual berhasil diambil",
  "data": {...} // Seller resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/sellers/{id} - Update seller

Update seller information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Seller ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| nama_toko | string | Shop name (max: 255 characters) |
| deskripsi_toko | string | Shop description |
| alamat_toko | string | Shop address |
| nomor_telepon_toko | string | Shop contact number |
| status_toko | string | Seller status (AKTIF/TIDAK_AKTIF/DIBLOKIR/SUSPEND) |
| rating | number | Seller rating (0.00 to 5.00) |
| jumlah_transaksi | integer | Number of completed transactions |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Penjual berhasil diperbarui",
  "data": {...} // Updated seller resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/sellers/{id} - Delete seller

Delete a specific seller account.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Seller ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Penjual berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Device Management API

### Device Resource Endpoints

**Base Endpoint**: `/api/devices`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for user device management.

#### GET /api/devices - List all devices

List all devices with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter devices by name or OS |
| user_id | integer | No | Filter by specific user |
| status | string | No | Filter by device status (TERPERCAYA/TIDAK_TERPERCAYA) |
| sort_by | string | No | Sort by field (created_at/updated_at/device_name) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data perangkat berhasil diambil",
  "data": [...], // Array of device resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/devices - Create new device

Register a new device for a user.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| device_id | string | Yes | Unique device identifier |
| id_user | integer | Yes | User ID (must exist in users table) |
| device_name | string | Yes | Device name |
| operating_system | string | Yes | Operating system |
| app_version | string | No | Application version |
| push_token | string | No | Push notification token |
| adalah_device_terpercaya | boolean | No | Whether device is trusted (default: false) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Perangkat berhasil didaftarkan",
  "data": {...} // Created device resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/devices/{id} - Get specific device

Retrieve a specific device's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Device ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data perangkat berhasil diambil",
  "data": {...} // Device resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/devices/{id} - Update device

Update device information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Device ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| device_id | string | Unique device identifier |
| id_user | integer | User ID (must exist in users table) |
| device_name | string | Device name |
| operating_system | string | Operating system |
| app_version | string | Application version |
| push_token | string | Push notification token |
| adalah_device_terpercaya | boolean | Whether device is trusted |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Perangkat berhasil diperbarui",
  "data": {...} // Updated device resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/devices/{id} - Delete device

Delete a specific device registration.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Device ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Perangkat berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Trust Device Endpoint

**Method**: `POST`  
**Endpoint**: `/api/devices/{id}/trust`  
**Authentication**: Bearer token (Laravel Sanctum)

Mark a device as trusted by the user.

#### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

#### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Device ID |

#### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Perangkat berhasil ditandai sebagai terpercaya"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Verification Management API

### Verification Resource Endpoints

**Base Endpoint**: `/api/verifications`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for user verification management.

#### GET /api/verifications - List all verifications

List all verifications with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| user_id | integer | No | Filter by specific user |
| jenis_verifikasi | string | No | Filter by verification type (EMAIL/TELEPON/KTP/NPWP) |
| status | string | No | Filter by verification status (TERVERIFIKASI/TIDAK_TERVERIFIKASI/KADALUWARSA) |
| sort_by | string | No | Sort by field (created_at/updated_at/kedaluwarsa_pada) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data verifikasi berhasil diambil",
  "data": [...], // Array of verification resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/verifications - Create new verification

Create a new verification record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_user | integer | No | User ID (must exist in users table) |
| jenis_verifikasi | string | Yes | Verification type (EMAIL/TELEPON/KTP/NPWP) |
| nilai_verifikasi | string | Yes | Verification value (email/phone number/etc.) |
| kode_verifikasi | string | Yes | 6-digit verification code |
| kedaluwarsa_pada | datetime | Yes | Expiration datetime for the code |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Verifikasi berhasil dibuat",
  "data": {...} // Created verification resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/verifications/{id} - Get specific verification

Retrieve a specific verification's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Verification ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data verifikasi berhasil diambil",
  "data": {...} // Verification resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/verifications/{id} - Update verification

Update verification information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Verification ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| jenis_verifikasi | string | Verification type (EMAIL/TELEPON/KTP/NPWP) |
| nilai_verifikasi | string | Verification value (email/phone number/etc.) |
| kode_verifikasi | string | 6-digit verification code |
| kedaluwarsa_pada | datetime | Expiration datetime for the code |
| telah_digunakan | boolean | Whether the code has been used |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Verifikasi berhasil diperbarui",
  "data": {...} // Updated verification resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/verifications/{id} - Delete verification

Delete a specific verification record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Verification ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Verifikasi berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Session Management API

### Session Resource Endpoints

**Base Endpoint**: `/api/sessions`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for user session management.

#### GET /api/sessions - List all sessions

List all sessions with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter sessions by IP or user agent |
| user_id | integer | No | Filter by specific user |
| ip_address | string | No | Filter by IP address |
| sort_by | string | No | Sort by field (last_activity/created_at) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data sesi berhasil diambil",
  "data": [...], // Array of session resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/sessions - Create new session

Create a new session record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id | string | Yes | Session ID (must be unique) |
| id_user | integer | No | User ID (must exist in users table) |
| ip_address | string | No | IP address (max: 45 characters) |
| user_agent | string | No | User agent string |
| payload | string | Yes | Session payload |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Sesi berhasil dibuat",
  "data": {...} // Created session resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/sessions/{id} - Get specific session

Retrieve a specific session's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | string | Session ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data sesi berhasil diambil",
  "data": {...} // Session resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/sessions/{id} - Update session

Update session information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | string | Session ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| ip_address | string | IP address (max: 45 characters) |
| user_agent | string | User agent string |
| payload | string | Session payload |
| last_activity | integer | Last activity timestamp |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Sesi berhasil diperbarui",
  "data": {...} // Updated session resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/sessions/{id} - Delete session

Delete a specific session record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | string | Session ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Sesi berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Address & Location APIs

### Address Resource Endpoints

**Base Endpoint**: `/api/addresses`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for user address management.

#### GET /api/addresses - List all addresses

List all addresses with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| user_id | integer | No | Filter by specific user |
| search | string | No | Search term to filter addresses by name or details |
| is_primary | boolean | No | Filter by primary address status |
| sort_by | string | No | Sort by field (created_at/updated_at/nama_penerima) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data alamat berhasil diambil",
  "data": [...], // Array of address resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/addresses - Create new address

Create a new address record for a user.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_user | integer | Yes | User ID (must exist in users table) |
| label_alamat | string | Yes | Address label (max: 255 characters) |
| nama_penerima | string | Yes | Recipient name (max: 255 characters) |
| nomor_telepon_penerima | string | Yes | Recipient phone number |
| alamat_lengkap | string | Yes | Complete address |
| provinsi_id | integer | Yes | Province ID (must exist in master_provinsi) |
| kota_id | integer | Yes | City ID (must exist in master_kota) |
| kecamatan_id | integer | Yes | District ID (must exist in master_kecamatan) |
| kode_pos_id | integer | Yes | Postal code ID (must exist in master_kode_pos) |
| catatan_pengiriman | string | No | Shipping notes |
| is_primary | boolean | No | Whether this is the primary address |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Alamat berhasil dibuat",
  "data": {...} // Created address resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/addresses/{id} - Get specific address

Retrieve a specific address's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Address ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data alamat berhasil diambil",
  "data": {...} // Address resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/addresses/{id} - Update address

Update address information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Address ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| label_alamat | string | Address label (max: 255 characters) |
| nama_penerima | string | Recipient name (max: 255 characters) |
| nomor_telepon_penerima | string | Recipient phone number |
| alamat_lengkap | string | Complete address |
| provinsi_id | integer | Province ID (must exist in master_provinsi) |
| kota_id | integer | City ID (must exist in master_kota) |
| kecamatan_id | integer | District ID (must exist in master_kecamatan) |
| kode_pos_id | integer | Postal code ID (must exist in master_kode_pos) |
| catatan_pengiriman | string | Shipping notes |
| is_primary | boolean | Whether this is the primary address |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Alamat berhasil diperbarui",
  "data": {...} // Updated address resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/addresses/{id} - Delete address

Delete a specific address record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Address ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Alamat berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Province Resource Endpoints

**Base Endpoint**: `/api/provinces`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for province management.

#### GET /api/provinces - List all provinces

List all provinces with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter provinces by name |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data provinsi berhasil diambil",
  "data": [...], // Array of province resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/provinces - Create new province

Create a new province record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| nama | string | Yes | Province name (must be unique) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Provinsi berhasil dibuat",
  "data": {...} // Created province resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/provinces/{id} - Get specific province

Retrieve a specific province's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Province ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data provinsi berhasil diambil",
  "data": {...} // Province resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/provinces/{id} - Update province

Update province information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Province ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| nama | string | Province name (must be unique) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Provinsi berhasil diperbarui",
  "data": {...} // Updated province resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/provinces/{id} - Delete province

Delete a specific province record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Province ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Provinsi berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### City Resource Endpoints

**Base Endpoint**: `/api/cities`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for city management.

#### GET /api/cities - List all cities

List all cities with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter cities by name |
| provinsi_id | integer | No | Filter by specific province |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data kota berhasil diambil",
  "data": [...], // Array of city resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/cities - Create new city

Create a new city record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| provinsi_id | integer | Yes | Province ID (must exist in master_provinsi) |
| nama | string | Yes | City name |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Kota berhasil dibuat",
  "data": {...} // Created city resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/cities/{id} - Get specific city

Retrieve a specific city's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | City ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data kota berhasil diambil",
  "data": {...} // City resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/cities/{id} - Update city

Update city information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | City ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| provinsi_id | integer | Province ID (must exist in master_provinsi) |
| nama | string | City name |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Kota berhasil diperbarui",
  "data": {...} // Updated city resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/cities/{id} - Delete city

Delete a specific city record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | City ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Kota berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### District Resource Endpoints

**Base Endpoint**: `/api/districts`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for district management.

#### GET /api/districts - List all districts

List all districts with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter districts by name |
| kota_id | integer | No | Filter by specific city |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data kecamatan berhasil diambil",
  "data": [...], // Array of district resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/districts - Create new district

Create a new district record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| kota_id | integer | Yes | City ID (must exist in master_kota) |
| nama | string | Yes | District name |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Kecamatan berhasil dibuat",
  "data": {...} // Created district resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/districts/{id} - Get specific district

Retrieve a specific district's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | District ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data kecamatan berhasil diambil",
  "data": {...} // District resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/districts/{id} - Update district

Update district information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | District ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| kota_id | integer | City ID (must exist in master_kota) |
| nama | string | District name |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Kecamatan berhasil diperbarui",
  "data": {...} // Updated district resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/districts/{id} - Delete district

Delete a specific district record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | District ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Kecamatan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Postal Code Resource Endpoints

**Base Endpoint**: `/api/postal-codes`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for postal code management.

#### GET /api/postal-codes - List all postal codes

List all postal codes with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter postal codes |
| kecamatan_id | integer | No | Filter by specific district |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data kode pos berhasil diambil",
  "data": [...], // Array of postal code resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/postal-codes - Create new postal code

Create a new postal code record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| kecamatan_id | integer | Yes | District ID (must exist in master_kecamatan) |
| kode | string | Yes | Postal code (must be unique) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Kode pos berhasil dibuat",
  "data": {...} // Created postal code resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/postal-codes/{id} - Get specific postal code

Retrieve a specific postal code's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Postal code ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data kode pos berhasil diambil",
  "data": {...} // Postal code resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/postal-codes/{id} - Update postal code

Update postal code information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Postal code ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| kecamatan_id | integer | District ID (must exist in master_kecamatan) |
| kode | string | Postal code (must be unique) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Kode pos berhasil diperbarui",
  "data": {...} // Updated postal code resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/postal-codes/{id} - Delete postal code

Delete a specific postal code record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Postal code ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Kode pos berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Shopping & Order APIs

### Cart Resource Endpoints

**Base Endpoint**: `/api/carts`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for shopping cart management.

#### GET /api/carts - List all carts

List all carts with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| user_id | integer | No | Filter by specific user |
| seller_id | integer | No | Filter by specific seller |
| session_id | string | No | Filter by specific session |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data keranjang belanja berhasil diambil",
  "data": [...], // Array of cart resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/carts - Create new cart

Create a new cart record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_user | integer | No | User ID (must exist in users table) |
| session_id | string | No | Session ID |
| id_seller | integer | Yes | Seller ID (must exist in penjual table) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Keranjang belanja berhasil dibuat",
  "data": {...} // Created cart resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/carts/{id} - Get specific cart

Retrieve a specific cart's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Cart ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data keranjang belanja berhasil diambil",
  "data": {...} // Cart resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/carts/{id} - Update cart

Update cart information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Cart ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| session_id | string | Session ID |
| id_seller | integer | Seller ID (must exist in penjual table) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Keranjang belanja berhasil diperbarui",
  "data": {...} // Updated cart resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/carts/{id} - Delete cart

Delete a specific cart record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Cart ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Keranjang belanja berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Cart Item Resource Endpoints

**Base Endpoint**: `/api/cart-items`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for cart item management.

#### GET /api/cart-items - List all cart items

List all cart items with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| cart_id | integer | No | Filter by specific cart |
| product_id | integer | No | Filter by specific product |
| user_id | integer | No | Filter by specific user |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data item keranjang berhasil diambil",
  "data": [...], // Array of cart item resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/cart-items - Create new cart item

Create a new cart item record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_cart | integer | Yes | Cart ID (must exist in keranjang_belanja table) |
| id_produk | integer | Yes | Product ID (must exist in tb_produk table) |
| id_harga_varian | integer | No | Variant price ID (must exist in harga_varian_produk table) |
| jumlah | integer | Yes | Quantity (min: 1) |
| catatan | string | No | Item notes |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Item keranjang berhasil dibuat",
  "data": {...} // Created cart item resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/cart-items/{id} - Get specific cart item

Retrieve a specific cart item's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Cart item ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data item keranjang berhasil diambil",
  "data": {...} // Cart item resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/cart-items/{id} - Update cart item

Update cart item information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Cart item ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_cart | integer | Cart ID (must exist in keranjang_belanja table) |
| id_produk | integer | Product ID (must exist in tb_produk table) |
| id_harga_varian | integer | Variant price ID (must exist in harga_varian_produk table) |
| jumlah | integer | Quantity (min: 1) |
| catatan | string | Item notes |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Item keranjang berhasil diperbarui",
  "data": {...} // Updated cart item resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/cart-items/{id} - Delete cart item

Delete a specific cart item record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Cart item ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Item keranjang berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Order Resource Endpoints

**Base Endpoint**: `/api/orders`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for order management.

#### GET /api/orders - List all orders

List all orders with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| customer_id | integer | No | Filter by specific customer |
| seller_id | integer | No | Filter by specific seller |
| status | string | No | Filter by order status |
| nomor_pesanan | string | No | Filter by specific order number |
| sort_by | string | No | Sort by field (created_at/updated_at/tanggal_pesanan) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pesanan berhasil diambil",
  "data": [...], // Array of order resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/orders - Create new order

Create a new order record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| nomor_pesanan | string | Yes | Order number (must be unique) |
| id_customer | integer | Yes | Customer ID (must exist in users table) |
| id_alamat_pengiriman | integer | Yes | Shipping address ID (must exist in alamat table) |
| total_harga | number | Yes | Total price |
| jumlah_item | integer | Yes | Number of items in order |
| status_pesanan | string | No | Order status (default: DIPROSES) |
| tanggal_pesanan | datetime | No | Order date (default: now) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Pesanan berhasil dibuat",
  "data": {...} // Created order resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/orders/{id} - Get specific order

Retrieve a specific order's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pesanan berhasil diambil",
  "data": {...} // Order resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/orders/{id} - Update order

Update order information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| nomor_pesanan | string | Order number (must be unique) |
| id_customer | integer | Customer ID (must exist in users table) |
| id_alamat_pengiriman | integer | Shipping address ID (must exist in alamat table) |
| total_harga | number | Total price |
| jumlah_item | integer | Number of items in order |
| status_pesanan | string | Order status |
| tanggal_pesanan | datetime | Order date |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pesanan berhasil diperbarui",
  "data": {...} // Updated order resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/orders/{id} - Delete order

Delete a specific order record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pesanan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Order Item Resource Endpoints

**Base Endpoint**: `/api/order-items`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for order item management.

#### GET /api/order-items - List all order items

List all order items with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| order_id | integer | No | Filter by specific order |
| product_id | integer | No | Filter by specific product |
| seller_id | integer | No | Filter by specific seller |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data item pesanan berhasil diambil",
  "data": [...], // Array of order item resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/order-items - Create new order item

Create a new order item record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_pesanan | integer | Yes | Order ID (must exist in pesanan table) |
| id_seller | integer | Yes | Seller ID (must exist in penjual table) |
| id_produk | integer | Yes | Product ID (must exist in tb_produk table) |
| id_harga_varian | integer | No | Variant price ID (must exist in harga_varian_produk table) |
| jumlah | integer | Yes | Quantity |
| harga_satuan | number | Yes | Unit price |
| subtotal | number | Yes | Subtotal (quantity * unit price) |
| catatan | string | No | Item notes |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Item pesanan berhasil dibuat",
  "data": {...} // Created order item resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/order-items/{id} - Get specific order item

Retrieve a specific order item's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order item ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data item pesanan berhasil diambil",
  "data": {...} // Order item resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/order-items/{id} - Update order item

Update order item information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order item ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_pesanan | integer | Order ID (must exist in pesanan table) |
| id_seller | integer | Seller ID (must exist in penjual table) |
| id_produk | integer | Product ID (must exist in tb_produk table) |
| id_harga_varian | integer | Variant price ID (must exist in harga_varian_produk table) |
| jumlah | integer | Quantity |
| harga_satuan | number | Unit price |
| subtotal | number | Subtotal (quantity * unit price) |
| catatan | string | Item notes |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Item pesanan berhasil diperbarui",
  "data": {...} // Updated order item resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/order-items/{id} - Delete order item

Delete a specific order item record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order item ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Item pesanan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Order Status History Resource Endpoints

**Base Endpoint**: `/api/order-status-history`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for order status history management.

#### GET /api/order-status-history - List all order status histories

List all order status histories with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| order_id | integer | No | Filter by specific order |
| status_sebelumnya | string | No | Filter by previous status |
| status_sekarang | string | No | Filter by current status |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data riwayat status pesanan berhasil diambil",
  "data": [...], // Array of order status history resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/order-status-history - Create new order status history

Create a new order status history record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_pesanan | integer | Yes | Order ID (must exist in pesanan table) |
| status_sebelumnya | string | No | Previous status |
| status_sekarang | string | Yes | Current status |
| catatan_perubahan | string | No | Change notes |
| dibuat_oleh | string | No | Created by (SYSTEM/ADMIN/SELLER/CUSTOMER) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Riwayat status pesanan berhasil dibuat",
  "data": {...} // Created order status history resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/order-status-history/{id} - Get specific order status history

Retrieve a specific order status history's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order status history ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data riwayat status pesanan berhasil diambil",
  "data": {...} // Order status history resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/order-status-history/{id} - Update order status history

Update order status history information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order status history ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_pesanan | integer | Order ID (must exist in pesanan table) |
| status_sebelumnya | string | Previous status |
| status_sekarang | string | Current status |
| catatan_perubahan | string | Change notes |
| dibuat_oleh | string | Created by (SYSTEM/ADMIN/SELLER/CUSTOMER) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Riwayat status pesanan berhasil diperbarui",
  "data": {...} // Updated order status history resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/order-status-history/{id} - Delete order status history

Delete a specific order status history record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order status history ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Riwayat status pesanan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Payment & Shipping APIs

### Shipping Method Resource Endpoints

**Base Endpoint**: `/api/shipping-methods`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for shipping method management.

#### GET /api/shipping-methods - List all shipping methods

List all shipping methods with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter shipping methods |
| tipe_layanan | string | No | Filter by service type |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data metode pengiriman berhasil diambil",
  "data": [...], // Array of shipping method resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/shipping-methods - Create new shipping method

Create a new shipping method record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| nama_kurir | string | Yes | Courier name (max: 255 characters) |
| tipe_layanan | string | Yes | Service type |
| deskripsi_layanan | string | No | Service description |
| biaya_dasar | number | No | Base cost |
| estimasi_pengiriman | string | No | Delivery time estimate |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Metode pengiriman berhasil dibuat",
  "data": {...} // Created shipping method resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/shipping-methods/{id} - Get specific shipping method

Retrieve a specific shipping method's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Shipping method ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data metode pengiriman berhasil diambil",
  "data": {...} // Shipping method resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/shipping-methods/{id} - Update shipping method

Update shipping method information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Shipping method ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| nama_kurir | string | Courier name (max: 255 characters) |
| tipe_layanan | string | Service type |
| deskripsi_layanan | string | Service description |
| biaya_dasar | number | Base cost |
| estimasi_pengiriman | string | Delivery time estimate |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Metode pengiriman berhasil diperbarui",
  "data": {...} // Updated shipping method resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/shipping-methods/{id} - Delete shipping method

Delete a specific shipping method record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Shipping method ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Metode pengiriman berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Shipping Rate Resource Endpoints

**Base Endpoint**: `/api/shipping-rates`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for shipping rate management.

#### GET /api/shipping-rates - List all shipping rates

List all shipping rates with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| kurir_id | integer | No | Filter by specific courier |
| kota_asal_id | integer | No | Filter by specific origin city |
| kota_tujuan_id | integer | No | Filter by specific destination city |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data tarif pengiriman berhasil diambil",
  "data": [...], // Array of shipping rate resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/shipping-rates - Create new shipping rate

Create a new shipping rate record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_kurir | integer | Yes | Courier ID (must exist in metode_pengiriman table) |
| id_kota_asal | integer | Yes | Origin city ID (must exist in master_kota table) |
| id_kota_tujuan | integer | Yes | Destination city ID (must exist in master_kota table) |
| biaya_pengiriman | number | Yes | Shipping cost |
| estimasi_hari | integer | Yes | Estimated days for delivery |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Tarif pengiriman berhasil dibuat",
  "data": {...} // Created shipping rate resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/shipping-rates/{id} - Get specific shipping rate

Retrieve a specific shipping rate's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Shipping rate ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data tarif pengiriman berhasil diambil",
  "data": {...} // Shipping rate resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/shipping-rates/{id} - Update shipping rate

Update shipping rate information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Shipping rate ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_kurir | integer | Courier ID (must exist in metode_pengiriman table) |
| id_kota_asal | integer | Origin city ID (must exist in master_kota table) |
| id_kota_tujuan | integer | Destination city ID (must exist in master_kota table) |
| biaya_pengiriman | number | Shipping cost |
| estimasi_hari | integer | Estimated days for delivery |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Tarif pengiriman berhasil diperbarui",
  "data": {...} // Updated shipping rate resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/shipping-rates/{id} - Delete shipping rate

Delete a specific shipping rate record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Shipping rate ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Tarif pengiriman berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Order Shipping Resource Endpoints

**Base Endpoint**: `/api/order-shipping`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for order shipping management.

#### GET /api/order-shipping - List all order shipping records

List all order shipping records with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| order_id | integer | No | Filter by specific order |
| kurir_id | integer | No | Filter by specific courier |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pengiriman pesanan berhasil diambil",
  "data": [...], // Array of order shipping resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/order-shipping - Create new order shipping

Create a new order shipping record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_pesanan | integer | Yes | Order ID (must exist in pesanan table) |
| id_kurir | integer | Yes | Courier ID (must exist in metode_pengiriman table) |
| nama_kurir | string | Yes | Courier name (max: 255 characters) |
| layanan_kurir | string | No | Courier service type |
| biaya_pengiriman | number | Yes | Shipping cost |
| estimasi_pengiriman | string | No | Delivery time estimate |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Pengiriman pesanan berhasil dibuat",
  "data": {...} // Created order shipping resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/order-shipping/{id} - Get specific order shipping

Retrieve a specific order shipping's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order shipping ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pengiriman pesanan berhasil diambil",
  "data": {...} // Order shipping resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/order-shipping/{id} - Update order shipping

Update order shipping information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order shipping ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_pesanan | integer | Order ID (must exist in pesanan table) |
| id_kurir | integer | Courier ID (must exist in metode_pengiriman table) |
| nama_kurir | string | Courier name (max: 255 characters) |
| layanan_kurir | string | Courier service type |
| biaya_pengiriman | number | Shipping cost |
| estimasi_pengiriman | string | Delivery time estimate |
| nomor_resi | string | Shipping receipt number |
| status_pengiriman | string | Shipping status |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pengiriman pesanan berhasil diperbarui",
  "data": {...} // Updated order shipping resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/order-shipping/{id} - Delete order shipping

Delete a specific order shipping record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order shipping ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pengiriman pesanan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Payment Method Resource Endpoints

**Base Endpoint**: `/api/payment-methods`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for payment method management.

#### GET /api/payment-methods - List all payment methods

List all payment methods with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter payment methods |
| tipe_pembayaran | string | No | Filter by payment type |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data metode pembayaran berhasil diambil",
  "data": [...], // Array of payment method resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/payment-methods - Create new payment method

Create a new payment method record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| nama_pembayaran | string | Yes | Payment method name (max: 255 characters) |
| tipe_pembayaran | string | Yes | Payment type (TRANSFER_BANK/E_WALLET/VIRTUAL_ACCOUNT/CREDIT_CARD/COD/QRIS) |
| provider_pembayaran | string | Yes | Payment provider name (max: 255 characters) |
| deskripsi_pembayaran | string | No | Payment method description |
| biaya_transaksi | number | No | Transaction fee |
| status_pembayaran | string | No | Payment method status (AKTIF/TIDAK_AKTIF) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Metode pembayaran berhasil dibuat",
  "data": {...} // Created payment method resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/payment-methods/{id} - Get specific payment method

Retrieve a specific payment method's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment method ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data metode pembayaran berhasil diambil",
  "data": {...} // Payment method resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/payment-methods/{id} - Update payment method

Update payment method information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment method ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| nama_pembayaran | string | Payment method name (max: 255 characters) |
| tipe_pembayaran | string | Payment type (TRANSFER_BANK/E_WALLET/VIRTUAL_ACCOUNT/CREDIT_CARD/COD/QRIS) |
| provider_pembayaran | string | Payment provider name (max: 255 characters) |
| deskripsi_pembayaran | string | Payment method description |
| biaya_transaksi | number | Transaction fee |
| status_pembayaran | string | Payment method status (AKTIF/TIDAK_AKTIF) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Metode pembayaran berhasil diperbarui",
  "data": {...} // Updated payment method resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/payment-methods/{id} - Delete payment method

Delete a specific payment method record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment method ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Metode pembayaran berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Payment Transaction Resource Endpoints

**Base Endpoint**: `/api/payment-transactions`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for payment transaction management.

#### GET /api/payment-transactions - List all payment transactions

List all payment transactions with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| order_id | integer | No | Filter by specific order |
| payment_method_id | integer | No | Filter by specific payment method |
| status_transaksi | string | No | Filter by transaction status |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data transaksi pembayaran berhasil diambil",
  "data": [...], // Array of payment transaction resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/payment-transactions - Create new payment transaction

Create a new payment transaction record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_pesanan | integer | Yes | Order ID (must exist in pesanan table) |
| id_metode_pembayaran | integer | Yes | Payment method ID (must exist in metode_pembayaran table) |
| referensi_id | string | Yes | Reference ID (must be unique) |
| jumlah_pembayaran | number | Yes | Payment amount |
| status_transaksi | string | No | Transaction status (default: PENDING) |
| tanggal_transaksi | datetime | No | Transaction date (default: now) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Transaksi pembayaran berhasil dibuat",
  "data": {...} // Created payment transaction resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/payment-transactions/{id} - Get specific payment transaction

Retrieve a specific payment transaction's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment transaction ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data transaksi pembayaran berhasil diambil",
  "data": {...} // Payment transaction resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/payment-transactions/{id} - Update payment transaction

Update payment transaction information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment transaction ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_pesanan | integer | Order ID (must exist in pesanan table) |
| id_metode_pembayaran | integer | Payment method ID (must exist in metode_pembayaran table) |
| referensi_id | string | Reference ID (must be unique) |
| jumlah_pembayaran | number | Payment amount |
| status_transaksi | string | Transaction status |
| tanggal_transaksi | datetime | Transaction date |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Transaksi pembayaran berhasil diperbarui",
  "data": {...} // Updated payment transaction resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/payment-transactions/{id} - Delete payment transaction

Delete a specific payment transaction record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment transaction ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Transaksi pembayaran berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Payment Log Resource Endpoints

**Base Endpoint**: `/api/payment-logs`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for payment log management.

#### GET /api/payment-logs - List all payment logs

List all payment logs with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| transaction_id | integer | No | Filter by specific transaction |
| tipe_log | string | No | Filter by log type |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data log pembayaran berhasil diambil",
  "data": [...], // Array of payment log resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/payment-logs - Create new payment log

Create a new payment log record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_transaksi_pembayaran | integer | Yes | Payment transaction ID (must exist in transaksi_pembayaran table) |
| tipe_log | string | Yes | Log type (REQUEST/RESPONSE/CALLBACK/ERROR) |
| konten_log | string | Yes | Log content |
| timestamp_log | datetime | No | Log timestamp (default: now) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Log pembayaran berhasil dibuat",
  "data": {...} // Created payment log resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/payment-logs/{id} - Get specific payment log

Retrieve a specific payment log's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment log ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data log pembayaran berhasil diambil",
  "data": {...} // Payment log resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/payment-logs/{id} - Update payment log

Update payment log information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment log ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_transaksi_pembayaran | integer | Payment transaction ID (must exist in transaksi_pembayaran table) |
| tipe_log | string | Log type (REQUEST/RESPONSE/CALLBACK/ERROR) |
| konten_log | string | Log content |
| timestamp_log | datetime | Log timestamp |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Log pembayaran berhasil diperbarui",
  "data": {...} // Updated payment log resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/payment-logs/{id} - Delete payment log

Delete a specific payment log record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment log ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Log pembayaran berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Review & Feedback APIs

### Product Review Resource Endpoints

**Base Endpoint**: `/api/product-reviews`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for product review management.

#### GET /api/product-reviews - List all product reviews

List all product reviews with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| product_id | integer | No | Filter by specific product |
| seller_id | integer | No | Filter by specific seller |
| buyer_id | integer | No | Filter by specific buyer |
| rating | integer | No | Filter by rating value |
| sort_by | string | No | Sort by field (created_at/updated_at/rating) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data ulasan produk berhasil diambil",
  "data": [...], // Array of review resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/product-reviews - Create new product review

Create a new product review.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_produk | integer | Yes | Product ID (must exist in tb_produk table) |
| id_harga_varian | integer | No | Variant price ID (must exist in harga_varian_produk table) |
| id_pembeli | integer | Yes | Buyer ID (must exist in users table) |
| rating | integer | Yes | Rating value (1-5) |
| komentar | string | No | Review comment |
| judul | string | No | Review title |
| status_ulasan | string | No | Review status (default: AKTIF) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Ulasan produk berhasil dibuat",
  "data": {...} // Created review resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/product-reviews/{id} - Get specific product review

Retrieve a specific product review's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data ulasan produk berhasil diambil",
  "data": {...} // Review resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/product-reviews/{id} - Update product review

Update product review information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_produk | integer | Product ID (must exist in tb_produk table) |
| id_harga_varian | integer | Variant price ID (must exist in harga_varian_produk table) |
| id_pembeli | integer | Buyer ID (must exist in users table) |
| rating | integer | Rating value (1-5) |
| komentar | string | Review comment |
| judul | string | Review title |
| status_ulasan | string | Review status |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Ulasan produk berhasil diperbarui",
  "data": {...} // Updated review resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/product-reviews/{id} - Delete product review

Delete a specific product review record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Ulasan produk berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Review Media Resource Endpoints

**Base Endpoint**: `/api/review-media`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for review media management.

#### GET /api/review-media - List all review media

List all review media with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| review_id | integer | No | Filter by specific review |
| tipe_media | string | No | Filter by media type |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data media ulasan berhasil diambil",
  "data": [...], // Array of review media resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/review-media - Create new review media

Create a new review media record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_review | integer | Yes | Review ID (must exist in ulasan_produk table) |
| tipe_media | string | Yes | Media type (GAMBAR/VIDEO) |
| url_media | string | Yes | Media URL (max: 2048 characters) |
| urutan | integer | No | Display order (default: 0) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Media ulasan berhasil dibuat",
  "data": {...} // Created review media resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/review-media/{id} - Get specific review media

Retrieve a specific review media's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review media ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data media ulasan berhasil diambil",
  "data": {...} // Review media resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/review-media/{id} - Update review media

Update review media information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review media ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_review | integer | Review ID (must exist in ulasan_produk table) |
| tipe_media | string | Media type (GAMBAR/VIDEO) |
| url_media | string | Media URL (max: 2048 characters) |
| urutan | integer | Display order |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Media ulasan berhasil diperbarui",
  "data": {...} // Updated review media resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/review-media/{id} - Delete review media

Delete a specific review media record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review media ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Media ulasan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Review Vote Resource Endpoints

**Base Endpoint**: `/api/review-votes`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for review vote management.

#### GET /api/review-votes - List all review votes

List all review votes with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| review_id | integer | No | Filter by specific review |
| user_id | integer | No | Filter by specific user |
| tipe_vote | string | No | Filter by vote type |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data suara ulasan berhasil diambil",
  "data": [...], // Array of review vote resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/review-votes - Create new review vote

Create a new review vote record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_review | integer | Yes | Review ID (must exist in ulasan_produk table) |
| id_user | integer | Yes | User ID (must exist in users table) |
| tipe_vote | string | Yes | Vote type (SUKA/TIDAK_SUKA) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Suara ulasan berhasil dibuat",
  "data": {...} // Created review vote resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/review-votes/{id} - Get specific review vote

Retrieve a specific review vote's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review vote ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data suara ulasan berhasil diambil",
  "data": {...} // Review vote resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/review-votes/{id} - Update review vote

Update review vote information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review vote ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_review | integer | Review ID (must exist in ulasan_produk table) |
| id_user | integer | User ID (must exist in users table) |
| tipe_vote | string | Vote type (SUKA/TIDAK_SUKA) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Suara ulasan berhasil diperbarui",
  "data": {...} // Updated review vote resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/review-votes/{id} - Delete review vote

Delete a specific review vote record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review vote ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Suara ulasan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Notification & Activity APIs

### Notification Resource Endpoints

**Base Endpoint**: `/api/notifications`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for user notification management.

#### GET /api/notifications - List all notifications

List all notifications with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| user_id | integer | No | Filter by specific user |
| tipe_notifikasi | string | No | Filter by notification type |
| status_dibaca | boolean | No | Filter by read status |
| sort_by | string | No | Sort by field (created_at/updated_at) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data notifikasi berhasil diambil",
  "data": [...], // Array of notification resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/notifications - Create new notification

Create a new notification record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_user | integer | Yes | User ID (must exist in users table) |
| tipe_notifikasi | string | Yes | Notification type (PESANAN/PEMBAYARAN/PENGIRIMAN/ULASAN/SYSTEM/CHAT) |
| judul_notifikasi | string | Yes | Notification title (max: 255 characters) |
| isi_notifikasi | string | Yes | Notification content |
| link_notifikasi | string | No | Notification link |
| status_dibaca | boolean | No | Whether notification has been read (default: false) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Notifikasi berhasil dibuat",
  "data": {...} // Created notification resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/notifications/{id} - Get specific notification

Retrieve a specific notification's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Notification ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data notifikasi berhasil diambil",
  "data": {...} // Notification resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/notifications/{id} - Update notification

Update notification information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Notification ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| tipe_notifikasi | string | Notification type (PESANAN/PEMBAYARAN/PENGIRIMAN/ULASAN/SYSTEM/CHAT) |
| judul_notifikasi | string | Notification title (max: 255 characters) |
| isi_notifikasi | string | Notification content |
| link_notifikasi | string | Notification link |
| status_dibaca | boolean | Whether notification has been read |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Notifikasi berhasil diperbarui",
  "data": {...} // Updated notification resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/notifications/{id} - Delete notification

Delete a specific notification record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Notification ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Notifikasi berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### User Activity Resource Endpoints

**Base Endpoint**: `/api/activities`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for user activity management.

#### GET /api/activities - List all activities

List all activities with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| user_id | integer | No | Filter by specific user |
| tipe_aktivitas | string | No | Filter by activity type |
| tanggal_mulai | date | No | Filter by start date |
| tanggal_selesai | date | No | Filter by end date |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data aktivitas berhasil diambil",
  "data": [...], // Array of activity resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/activities - Create new activity

Create a new activity record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_user | integer | Yes | User ID (must exist in users table) |
| sesi_id | string | No | Session ID (max: 255 characters) |
| tipe_aktivitas | string | Yes | Activity type (LOGIN/LOGOUT/VIEW_PRODUK/CARI_PRODUK/TAMBAH_KERANJANG/CHECKOUT/PAYMENT/REVIEW/CHAT) |
| deskripsi_aktivitas | string | No | Activity description |
| ip_address | string | No | IP address of the activity |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Aktivitas berhasil dibuat",
  "data": {...} // Created activity resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/activities/{id} - Get specific activity

Retrieve a specific activity's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Activity ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data aktivitas berhasil diambil",
  "data": {...} // Activity resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/activities/{id} - Update activity

Update activity information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Activity ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| sesi_id | string | Session ID (max: 255 characters) |
| tipe_aktivitas | string | Activity type (LOGIN/LOGOUT/VIEW_PRODUK/CARI_PRODUK/TAMBAH_KERANJANG/CHECKOUT/PAYMENT/REVIEW/CHAT) |
| deskripsi_aktivitas | string | Activity description |
| ip_address | string | IP address of the activity |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Aktivitas berhasil diperbarui",
  "data": {...} // Updated activity resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/activities/{id} - Delete activity

Delete a specific activity record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Activity ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Aktivitas berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Search History Resource Endpoints

**Base Endpoint**: `/api/search-history`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for search history management.

#### GET /api/search-history - List all search histories

List all search histories with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| user_id | integer | No | Filter by specific user |
| kata_pencarian | string | No | Filter by search term |
| tanggal_mulai | date | No | Filter by start date |
| tanggal_selesai | date | No | Filter by end date |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data riwayat pencarian berhasil diambil",
  "data": [...], // Array of search history resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/search-history - Create new search history

Create a new search history record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_user | integer | Yes | User ID (must exist in users table) |
| kata_pencarian | string | Yes | Search term (max: 255 characters) |
| jumlah_hasil | integer | Yes | Number of search results (min: 0) |
| tipe_pencarian | string | No | Search type (PRODUK/PELANGGAN/PELANGGAN_LAINNYA) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Riwayat pencarian berhasil dibuat",
  "data": {...} // Created search history resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/search-history/{id} - Get specific search history

Retrieve a specific search history's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Search history ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data riwayat pencarian berhasil diambil",
  "data": {...} // Search history resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/search-history/{id} - Update search history

Update search history information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Search history ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| kata_pencarian | string | Search term (max: 255 characters) |
| jumlah_hasil | integer | Number of search results (min: 0) |
| tipe_pencarian | string | Search type (PRODUK/PELANGGAN/PELANGGAN_LAINNYA) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Riwayat pencarian berhasil diperbarui",
  "data": {...} // Updated search history resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/search-history/{id} - Delete search history

Delete a specific search history record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Search history ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Riwayat pencarian berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Admin & Reporting APIs

### Admin User Resource Endpoints

**Base Endpoint**: `/api/admin-users`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for admin user management.

#### GET /api/admin-users - List all admin users

List all admin users with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter admin users |
| role_admin | string | No | Filter by admin role |
| sort_by | string | No | Sort by field (created_at/updated_at) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pengguna admin berhasil diambil",
  "data": [...], // Array of admin user resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/admin-users - Create new admin user

Create a new admin user record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_user | integer | Yes | User ID (must exist in users table) |
| role_admin | string | Yes | Admin role (SUPER_ADMIN/ADMIN_CONTENT/ADMIN_FINANCE/ADMIN_CUSTOMER/ADMIN_LOGISTIC) |
| permissions | json | No | Admin permissions |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Pengguna admin berhasil dibuat",
  "data": {...} // Created admin user resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/admin-users/{id} - Get specific admin user

Retrieve a specific admin user's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Admin user ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pengguna admin berhasil diambil",
  "data": {...} // Admin user resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/admin-users/{id} - Update admin user

Update admin user information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Admin user ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| role_admin | string | Admin role (SUPER_ADMIN/ADMIN_CONTENT/ADMIN_FINANCE/ADMIN_CUSTOMER/ADMIN_LOGISTIC) |
| permissions | json | Admin permissions |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pengguna admin berhasil diperbarui",
  "data": {...} // Updated admin user resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/admin-users/{id} - Delete admin user

Delete a specific admin user record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Admin user ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pengguna admin berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Seller Report Resource Endpoints

**Base Endpoint**: `/api/seller-reports`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for seller report management.

#### GET /api/seller-reports - List all seller reports

List all seller reports with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| seller_id | integer | No | Filter by specific seller |
| tipe_laporan | string | No | Filter by report type |
| periode_laporan | date | No | Filter by report period |
| sort_by | string | No | Sort by field (created_at/updated_at/periode_laporan) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data laporan penjual berhasil diambil",
  "data": [...], // Array of seller report resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/seller-reports - Create new seller report

Create a new seller report record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_seller | integer | Yes | Seller ID (must exist in penjual table) |
| tipe_laporan | string | Yes | Report type (HARIAN/MINGGUAN/BULANAN/TAHUNAN) |
| periode_laporan | date | Yes | Report period |
| data_laporan | json | No | Report data |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Laporan penjual berhasil dibuat",
  "data": {...} // Created seller report resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/seller-reports/{id} - Get specific seller report

Retrieve a specific seller report's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Seller report ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data laporan penjual berhasil diambil",
  "data": {...} // Seller report resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/seller-reports/{id} - Update seller report

Update seller report information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Seller report ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_seller | integer | Seller ID (must exist in penjual table) |
| tipe_laporan | string | Report type (HARIAN/MINGGUAN/BULANAN/TAHUNAN) |
| periode_laporan | date | Report period |
| data_laporan | json | Report data |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Laporan penjual berhasil diperbarui",
  "data": {...} // Updated seller report resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/seller-reports/{id} - Delete seller report

Delete a specific seller report record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Seller report ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Laporan penjual berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Sales Report Resource Endpoints

**Base Endpoint**: `/api/sales-reports`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for sales report management.

#### GET /api/sales-reports - List all sales reports

List all sales reports with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| tipe_laporan | string | No | Filter by report type |
| periode_laporan | date | No | Filter by report period |
| sort_by | string | No | Sort by field (created_at/updated_at/periode_laporan) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data laporan penjualan berhasil diambil",
  "data": [...], // Array of sales report resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/sales-reports - Create new sales report

Create a new sales report record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| tipe_laporan | string | Yes | Report type (GLOBAL/KATEGORI/PROVINSI/METODE_PEMBAYARAN) |
| periode_laporan | date | Yes | Report period |
| data_laporan | json | No | Report data |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Laporan penjualan berhasil dibuat",
  "data": {...} // Created sales report resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/sales-reports/{id} - Get specific sales report

Retrieve a specific sales report's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Sales report ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data laporan penjualan berhasil diambil",
  "data": {...} // Sales report resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/sales-reports/{id} - Update sales report

Update sales report information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Sales report ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| tipe_laporan | string | Report type (GLOBAL/KATEGORI/PROVINSI/METODE_PEMBAYARAN) |
| periode_laporan | date | Report period |
| data_laporan | json | Report data |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Laporan penjualan berhasil diperbarui",
  "data": {...} // Updated sales report resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/sales-reports/{id} - Delete sales report

Delete a specific sales report record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Sales report ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Laporan penjualan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Chat System APIs

### Chat Conversation Resource Endpoints

**Base Endpoint**: `/api/chat-conversations`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for chat conversation management.

#### GET /api/chat-conversations - List all chat conversations

List all chat conversations with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| tipe | string | No | Filter by conversation type |
| owner_user_id | integer | No | Filter by conversation owner |
| sort_by | string | No | Sort by field (created_at/updated_at) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data percakapan chat berhasil diambil",
  "data": [...], // Array of chat conversation resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/chat-conversations - Create new chat conversation

Create a new chat conversation record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| tipe | string | Yes | Conversation type (PRIVATE/GROUP/ORDER_SUPPORT,PRODUCT_SUPPORT,SYSTEM) |
| judul | string | No | Conversation title (max: 512 characters) |
| owner_user_id | integer | No | Owner user ID (must exist in users table) |
| deskripsi | string | No | Conversation description |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Percakapan chat berhasil dibuat",
  "data": {...} // Created chat conversation resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/chat-conversations/{id} - Get specific chat conversation

Retrieve a specific chat conversation's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat conversation ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data percakapan chat berhasil diambil",
  "data": {...} // Chat conversation resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/chat-conversations/{id} - Update chat conversation

Update chat conversation information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat conversation ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| tipe | string | Conversation type (PRIVATE/GROUP/ORDER_SUPPORT,PRODUCT_SUPPORT,SYSTEM) |
| judul | string | Conversation title (max: 512 characters) |
| owner_user_id | integer | Owner user ID (must exist in users table) |
| deskripsi | string | Conversation description |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Percakapan chat berhasil diperbarui",
  "data": {...} // Updated chat conversation resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/chat-conversations/{id} - Delete chat conversation

Delete a specific chat conversation record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat conversation ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Percakapan chat berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Chat Participant Resource Endpoints

**Base Endpoint**: `/api/chat-participants`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for chat participant management.

#### GET /api/chat-participants - List all chat participants

List all chat participants with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| percakapan_id | integer | No | Filter by specific conversation |
| user_id | integer | No | Filter by specific user |
| shop_profile_id | integer | No | Filter by specific shop profile |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data peserta chat berhasil diambil",
  "data": [...], // Array of chat participant resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/chat-participants - Create new chat participant

Create a new chat participant record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| percakapan_id | integer | Yes | Conversation ID (must exist in chat_percakapan table) |
| user_id | integer | No | User ID (must exist in users table) |
| shop_profile_id | integer | No | Shop profile ID (must exist in penjual table) |
| status_partisipan | string | No | Participant status (AKTIF/BLOCKED) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Peserta chat berhasil dibuat",
  "data": {...} // Created chat participant resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/chat-participants/{id} - Get specific chat participant

Retrieve a specific chat participant's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat participant ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data peserta chat berhasil diambil",
  "data": {...} // Chat participant resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/chat-participants/{id} - Update chat participant

Update chat participant information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat participant ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| percakapan_id | integer | Conversation ID (must exist in chat_percakapan table) |
| user_id | integer | User ID (must exist in users table) |
| shop_profile_id | integer | Shop profile ID (must exist in penjual table) |
| status_partisipan | string | Participant status (AKTIF/BLOCKED) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Peserta chat berhasil diperbarui",
  "data": {...} // Updated chat participant resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/chat-participants/{id} - Delete chat participant

Delete a specific chat participant record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat participant ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Peserta chat berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Chat Message Resource Endpoints

**Base Endpoint**: `/api/chat-messages`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for chat message management.

#### GET /api/chat-messages - List all chat messages

List all chat messages with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| percakapan_id | integer | No | Filter by specific conversation |
| pengirim_user_id | integer | No | Filter by specific sender |
| pengirim_shop_profile_id | integer | No | Filter by specific shop sender |
| sort_by | string | No | Sort by field (created_at/updated_at) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pesan chat berhasil diambil",
  "data": [...], // Array of chat message resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/chat-messages - Create new chat message

Create a new chat message record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| percakapan_id | integer | Yes | Conversation ID (must exist in chat_percakapan table) |
| pengirim_user_id | integer | No | Sender user ID (must exist in users table) |
| pengirim_shop_profile_id | integer | No | Sender shop profile ID (must exist in penjual table) |
| isi_pesan | string | Yes | Message content |
| tipe_pesan | string | No | Message type (TEXT/IMAGE/AUDIO/VIDEO/DOCUMENT/FILE/LINK) |
| status_pesan | string | No | Message status (TERKIRIM/DITERIMA/DIBACA/GAGAL) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Pesan chat berhasil dibuat",
  "data": {...} // Created chat message resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/chat-messages/{id} - Get specific chat message

Retrieve a specific chat message's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat message ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pesan chat berhasil diambil",
  "data": {...} // Chat message resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/chat-messages/{id} - Update chat message

Update chat message information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat message ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| percakapan_id | integer | Conversation ID (must exist in chat_percakapan table) |
| pengirim_user_id | integer | Sender user ID (must exist in users table) |
| pengirim_shop_profile_id | integer | Sender shop profile ID (must exist in penjual table) |
| isi_pesan | string | Message content |
| tipe_pesan | string | Message type (TEXT/IMAGE/AUDIO/VIDEO/DOCUMENT/FILE/LINK) |
| status_pesan | string | Message status (TERKIRIM/DITERIMA/DIBACA/GAGAL) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pesan chat berhasil diperbarui",
  "data": {...} // Updated chat message resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/chat-messages/{id} - Delete chat message

Delete a specific chat message record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat message ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pesan chat berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Message Status Resource Endpoints

**Base Endpoint**: `/api/message-statuses`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for message status management.

#### GET /api/message-statuses - List all message statuses

List all message statuses with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| pesan_id | integer | No | Filter by specific message |
| user_id | integer | No | Filter by specific user |
| status | string | No | Filter by status |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data status pesan berhasil diambil",
  "data": [...], // Array of message status resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/message-statuses - Create new message status

Create a new message status record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| pesan_id | integer | Yes | Message ID (must exist in chat_pesan_chat table) |
| user_id | integer | Yes | User ID (must exist in users table) |
| status | string | Yes | Status (TERKIRIM/DITERIMA/DIBACA/GAGAL) |
| timestamp_status | datetime | No | Status timestamp (default: now) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Status pesan berhasil dibuat",
  "data": {...} // Created message status resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/message-statuses/{id} - Get specific message status

Retrieve a specific message status's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message status ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data status pesan berhasil diambil",
  "data": {...} // Message status resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/message-statuses/{id} - Update message status

Update message status information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message status ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| pesan_id | integer | Message ID (must exist in chat_pesan_chat table) |
| user_id | integer | User ID (must exist in users table) |
| status | string | Status (TERKIRIM/DITERIMA/DIBACA/GAGAL) |
| timestamp_status | datetime | Status timestamp |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Status pesan berhasil diperbarui",
  "data": {...} // Updated message status resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/message-statuses/{id} - Delete message status

Delete a specific message status record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message status ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Status pesan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Message Reaction Resource Endpoints

**Base Endpoint**: `/api/message-reactions`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for message reaction management.

#### GET /api/message-reactions - List all message reactions

List all message reactions with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| pesan_id | integer | No | Filter by specific message |
| user_id | integer | No | Filter by specific user |
| reaksi | string | No | Filter by reaction |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data reaksi pesan berhasil diambil",
  "data": [...], // Array of message reaction resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/message-reactions - Create new message reaction

Create a new message reaction record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| pesan_id | integer | Yes | Message ID (must exist in chat_pesan_chat table) |
| user_id | integer | Yes | User ID (must exist in users table) |
| reaksi | string | Yes | Reaction (max: 10 characters) |
| timestamp_reaksi | datetime | No | Reaction timestamp (default: now) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Reaksi pesan berhasil dibuat",
  "data": {...} // Created message reaction resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/message-reactions/{id} - Get specific message reaction

Retrieve a specific message reaction's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message reaction ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data reaksi pesan berhasil diambil",
  "data": {...} // Message reaction resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/message-reactions/{id} - Update message reaction

Update message reaction information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message reaction ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| pesan_id | integer | Message ID (must exist in chat_pesan_chat table) |
| user_id | integer | User ID (must exist in users table) |
| reaksi | string | Reaction (max: 10 characters) |
| timestamp_reaksi | datetime | Reaction timestamp |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Reaksi pesan berhasil diperbarui",
  "data": {...} // Updated message reaction resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/message-reactions/{id} - Delete message reaction

Delete a specific message reaction record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message reaction ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Reaksi pesan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Message Edit Resource Endpoints

**Base Endpoint**: `/api/message-edits`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for message edit management.

#### GET /api/message-edits - List all message edits

List all message edits with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| pesan_id | integer | No | Filter by specific message |
| editor_id | integer | No | Filter by specific editor |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data edit pesan berhasil diambil",
  "data": [...], // Array of message edit resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/message-edits - Create new message edit

Create a new message edit record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| pesan_id | integer | Yes | Message ID (must exist in chat_pesan_chat table) |
| editor_id | integer | Yes | Editor ID (must exist in users table) |
| isi_sebelumnya | string | Yes | Previous content (max: 1000 characters) |
| isi_baru | string | Yes | New content (max: 1000 characters) |
| timestamp_edit | datetime | No | Edit timestamp (default: now) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Edit pesan berhasil dibuat",
  "data": {...} // Created message edit resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/message-edits/{id} - Get specific message edit

Retrieve a specific message edit's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message edit ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data edit pesan berhasil diambil",
  "data": {...} // Message edit resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/message-edits/{id} - Update message edit

Update message edit information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message edit ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| pesan_id | integer | Message ID (must exist in chat_pesan_chat table) |
| editor_id | integer | Editor ID (must exist in users table) |
| isi_sebelumnya | string | Previous content (max: 1000 characters) |
| isi_baru | string | New content (max: 1000 characters) |
| timestamp_edit | datetime | Edit timestamp |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Edit pesan berhasil diperbarui",
  "data": {...} // Updated message edit resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/message-edits/{id} - Delete message edit

Delete a specific message edit record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message edit ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Edit pesan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Message Attachment Resource Endpoints

**Base Endpoint**: `/api/message-attachments`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for message attachment management.

#### GET /api/message-attachments - List all message attachments

List all message attachments with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| pesan_id | integer | No | Filter by specific message |
| tipe_lampiran | string | No | Filter by attachment type |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data lampiran pesan berhasil diambil",
  "data": [...], // Array of message attachment resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/message-attachments - Create new message attachment

Create a new message attachment record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| pesan_id | integer | Yes | Message ID (must exist in chat_pesan_chat table) |
| tipe_lampiran | string | Yes | Attachment type (IMAGE/VIDEO/AUDIO/DOCUMENT/FILE) |
| url_lampiran | string | Yes | Attachment URL (max: 2048 characters) |
| ukuran_lampiran | integer | No | Attachment size in bytes |
| nama_file | string | No | File name (max: 255 characters) |
| mime_type | string | No | MIME type (max: 255 characters) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Lampiran pesan berhasil dibuat",
  "data": {...} // Created message attachment resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/message-attachments/{id} - Get specific message attachment

Retrieve a specific message attachment's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message attachment ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data lampiran pesan berhasil diambil",
  "data": {...} // Message attachment resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/message-attachments/{id} - Update message attachment

Update message attachment information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message attachment ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| pesan_id | integer | Message ID (must exist in chat_pesan_chat table) |
| tipe_lampiran | string | Attachment type (IMAGE/VIDEO/AUDIO/DOCUMENT/FILE) |
| url_lampiran | string | Attachment URL (max: 2048 characters) |
| ukuran_lampiran | integer | Attachment size in bytes |
| nama_file | string | File name (max: 255 characters) |
| mime_type | string | MIME type (max: 255 characters) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Lampiran pesan berhasil diperbarui",
  "data": {...} // Updated message attachment resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/message-attachments/{id} - Delete message attachment

Delete a specific message attachment record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message attachment ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Lampiran pesan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Chat Product Reference Resource Endpoints

**Base Endpoint**: `/api/chat-product-references`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for chat product reference management.

#### GET /api/chat-product-references - List all chat product references

List all chat product references with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| pesan_id | integer | No | Filter by specific message |
| produk_id | integer | No | Filter by specific product |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data referensi produk chat berhasil diambil",
  "data": [...], // Array of chat product reference resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/chat-product-references - Create new chat product reference

Create a new chat product reference record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| pesan_id | integer | Yes | Message ID (must exist in chat_pesan_chat table) |
| produk_id | integer | Yes | Product ID (must exist in produk table) |
| posisi_mulai | integer | No | Start position in message |
| posisi_akhir | integer | No | End position in message |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Referensi produk chat berhasil dibuat",
  "data": {...} // Created chat product reference resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/chat-product-references/{id} - Get specific chat product reference

Retrieve a specific chat product reference's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat product reference ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data referensi produk chat berhasil diambil",
  "data": {...} // Chat product reference resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/chat-product-references/{id} - Update chat product reference

Update chat product reference information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat product reference ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| pesan_id | integer | Message ID (must exist in chat_pesan_chat table) |
| produk_id | integer | Product ID (must exist in produk table) |
| posisi_mulai | integer | Start position in message |
| posisi_akhir | integer | End position in message |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Referensi produk chat berhasil diperbarui",
  "data": {...} // Updated chat product reference resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/chat-product-references/{id} - Delete chat product reference

Delete a specific chat product reference record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat product reference ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Referensi produk chat berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Chat Order Reference Resource Endpoints

**Base Endpoint**: `/api/chat-order-references`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for chat order reference management.

#### GET /api/chat-order-references - List all chat order references

List all chat order references with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| pesan_id | integer | No | Filter by specific message |
| order_id | integer | No | Filter by specific order |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data referensi order chat berhasil diambil",
  "data": [...], // Array of chat order reference resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/chat-order-references - Create new chat order reference

Create a new chat order reference record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| pesan_id | integer | Yes | Message ID (must exist in chat_pesan_chat table) |
| order_id | integer | Yes | Order ID (must exist in orders table) |
| posisi_mulai | integer | No | Start position in message |
| posisi_akhir | integer | No | End position in message |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Referensi order chat berhasil dibuat",
  "data": {...} // Created chat order reference resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/chat-order-references/{id} - Get specific chat order reference

Retrieve a specific chat order reference's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat order reference ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data referensi order chat berhasil diambil",
  "data": {...} // Chat order reference resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/chat-order-references/{id} - Update chat order reference

Update chat order reference information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat order reference ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| pesan_id | integer | Message ID (must exist in chat_pesan_chat table) |
| order_id | integer | Order ID (must exist in orders table) |
| posisi_mulai | integer | Start position in message |
| posisi_akhir | integer | End position in message |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Referensi order chat berhasil diperbarui",
  "data": {...} // Updated chat order reference resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/chat-order-references/{id} - Delete chat order reference

Delete a specific chat order reference record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat order reference ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Referensi order chat berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Chat Report Resource Endpoints

**Base Endpoint**: `/api/chat-reports`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for chat report management.

#### GET /api/chat-reports - List all chat reports

List all chat reports with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| percakapan_id | integer | No | Filter by specific conversation |
| reporter_id | integer | No | Filter by specific reporter |
| tipe_laporan | string | No | Filter by report type |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data laporan chat berhasil diambil",
  "data": [...], // Array of chat report resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/chat-reports - Create new chat report

Create a new chat report record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| percakapan_id | integer | Yes | Conversation ID (must exist in chat_percakapan table) |
| reporter_id | integer | Yes | Reporter ID (must exist in users table) |
| alasan | string | Yes | Reason for report (max: 500 characters) |
| tipe_laporan | string | No | Report type (SPAM/HARASSMENT/INAPPROPRIATE_CONTENT/OTHER) |
| status_laporan | string | No | Report status (PENDING/RESOLVED/DISMISSED) |
| tanggapan_admin | string | No | Admin response |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Laporan chat berhasil dibuat",
  "data": {...} // Created chat report resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/chat-reports/{id} - Get specific chat report

Retrieve a specific chat report's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat report ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data laporan chat berhasil diambil",
  "data": {...} // Chat report resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/chat-reports/{id} - Update chat report

Update chat report information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat report ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| percakapan_id | integer | Conversation ID (must exist in chat_percakapan table) |
| reporter_id | integer | Reporter ID (must exist in users table) |
| alasan | string | Reason for report (max: 500 characters) |
| tipe_laporan | string | Report type (SPAM/HARASSMENT/INAPPROPRIATE_CONTENT/OTHER) |
| status_laporan | string | Report status (PENDING/RESOLVED/DISMISSED) |
| tanggapan_admin | string | Admin response |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Laporan chat berhasil diperbarui",
  "data": {...} // Updated chat report resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/chat-reports/{id} - Delete chat report

Delete a specific chat report record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat report ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Laporan chat berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## System Settings API

### System Setting Resource Endpoints

**Base Endpoint**: `/api/system-settings`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for system setting management.

#### GET /api/system-settings - List all system settings

List all system settings with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter settings |
| kunci_pengaturan | string | No | Filter by setting key |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pengaturan sistem berhasil diambil",
  "data": [...], // Array of system setting resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/system-settings - Create new system setting

Create a new system setting record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| kunci_pengaturan | string | Yes | Setting key (max: 255 characters, must be unique) |
| nilai_pengaturan | string | Yes | Setting value |
| tipe_pengaturan | string | Yes | Setting type (STRING/NUMBER/BOOLEAN/JSON) |
| deskripsi_pengaturan | string | No | Setting description |
| grup_pengaturan | string | No | Setting group |
| dapat_diubah | boolean | No | Whether setting can be modified (default: true) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Pengaturan sistem berhasil dibuat",
  "data": {...} // Created system setting resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/system-settings/{id} - Get specific system setting

Retrieve a specific system setting's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | System setting ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pengaturan sistem berhasil diambil",
  "data": {...} // System setting resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/system-settings/{id} - Update system setting

Update system setting information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | System setting ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| kunci_pengaturan | string | Setting key (max: 255 characters, must be unique) |
| nilai_pengaturan | string | Setting value |
| tipe_pengaturan | string | Setting type (STRING/NUMBER/BOOLEAN/JSON) |
| deskripsi_pengaturan | string | Setting description |
| grup_pengaturan | string | Setting group |
| dapat_diubah | boolean | Whether setting can be modified |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pengaturan sistem berhasil diperbarui",
  "data": {...} // Updated system setting resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/system-settings/{id} - Delete system setting

Delete a specific system setting record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | System setting ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pengaturan sistem berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Response Format

All API responses follow a consistent format:

```json
{
  "success": true/false,
  "message": "descriptive message about the operation",
  "data": {...}, // optional data field
  "errors": {...}, // optional errors field for validation errors
  "pagination": {...} // optional pagination information
}
```

## Error Codes

| HTTP Code | Description |
|-----------|-------------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request - Invalid input data |
| 401 | Unauthorized - Invalid or missing token |
| 403 | Forbidden - Insufficient permissions |
| 404 | Not Found - Resource does not exist |
| 422 | Unprocessable Entity - Validation failed |
| 500 | Internal Server Error - Unexpected server error |
## Seller Management API

### Seller Resource Endpoints

**Base Endpoint**: `/api/sellers`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for seller management.

#### GET /api/sellers - List all sellers

List all sellers with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter sellers by name or shop name |
| status | string | No | Filter by seller status (AKTIF/TIDAK_AKTIF/DIBLOKIR/SUSPEND) |
| sort_by | string | No | Sort by field (created_at/updated_at/nama_toko) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data penjual berhasil diambil",
  "data": [...], // Array of seller resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/sellers - Create new seller

Create a new seller account.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_user | integer | Yes | User ID (must exist in users table) |
| nama_toko | string | Yes | Shop name (max: 255 characters) |
| deskripsi_toko | string | No | Shop description |
| alamat_toko | string | No | Shop address |
| nomor_telepon_toko | string | No | Shop contact number |
| status_toko | string | No | Seller status (AKTIF/TIDAK_AKTIF/DIBLOKIR/SUSPEND) |
| rating | number | No | Seller rating (0.00 to 5.00) |
| jumlah_transaksi | integer | No | Number of completed transactions |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Penjual berhasil dibuat",
  "data": {...} // Created seller resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/sellers/{id} - Get specific seller

Retrieve a specific seller's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Seller ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data penjual berhasil diambil",
  "data": {...} // Seller resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/sellers/{id} - Update seller

Update seller information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Seller ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| nama_toko | string | Shop name (max: 255 characters) |
| deskripsi_toko | string | Shop description |
| alamat_toko | string | Shop address |
| nomor_telepon_toko | string | Shop contact number |
| status_toko | string | Seller status (AKTIF/TIDAK_AKTIF/DIBLOKIR/SUSPEND) |
| rating | number | Seller rating (0.00 to 5.00) |
| jumlah_transaksi | integer | Number of completed transactions |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Penjual berhasil diperbarui",
  "data": {...} // Updated seller resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/sellers/{id} - Delete seller

Delete a specific seller account.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Seller ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Penjual berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Device Management API

### Device Resource Endpoints

**Base Endpoint**: `/api/devices`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for user device management.

#### GET /api/devices - List all devices

List all devices with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter devices by name or OS |
| user_id | integer | No | Filter by specific user |
| status | string | No | Filter by device status (TERPERCAYA/TIDAK_TERPERCAYA) |
| sort_by | string | No | Sort by field (created_at/updated_at/device_name) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data perangkat berhasil diambil",
  "data": [...], // Array of device resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/devices - Create new device

Register a new device for a user.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| device_id | string | Yes | Unique device identifier |
| id_user | integer | Yes | User ID (must exist in users table) |
| device_name | string | Yes | Device name |
| operating_system | string | Yes | Operating system |
| app_version | string | No | Application version |
| push_token | string | No | Push notification token |
| adalah_device_terpercaya | boolean | No | Whether device is trusted (default: false) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Perangkat berhasil didaftarkan",
  "data": {...} // Created device resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/devices/{id} - Get specific device

Retrieve a specific device's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Device ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data perangkat berhasil diambil",
  "data": {...} // Device resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/devices/{id} - Update device

Update device information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Device ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| device_id | string | Unique device identifier |
| id_user | integer | User ID (must exist in users table) |
| device_name | string | Device name |
| operating_system | string | Operating system |
| app_version | string | Application version |
| push_token | string | Push notification token |
| adalah_device_terpercaya | boolean | Whether device is trusted |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Perangkat berhasil diperbarui",
  "data": {...} // Updated device resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/devices/{id} - Delete device

Delete a specific device registration.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Device ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Perangkat berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Trust Device Endpoint

**Method**: `POST`  
**Endpoint**: `/api/devices/{id}/trust`  
**Authentication**: Bearer token (Laravel Sanctum)

Mark a device as trusted by the user.

#### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

#### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Device ID |

#### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Perangkat berhasil ditandai sebagai terpercaya"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Verification Management API

### Verification Resource Endpoints

**Base Endpoint**: `/api/verifications`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for user verification management.

#### GET /api/verifications - List all verifications

List all verifications with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| user_id | integer | No | Filter by specific user |
| jenis_verifikasi | string | No | Filter by verification type (EMAIL/TELEPON/KTP/NPWP) |
| status | string | No | Filter by verification status (TERVERIFIKASI/TIDAK_TERVERIFIKASI/KADALUWARSA) |
| sort_by | string | No | Sort by field (created_at/updated_at/kedaluwarsa_pada) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data verifikasi berhasil diambil",
  "data": [...], // Array of verification resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/verifications - Create new verification

Create a new verification record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_user | integer | No | User ID (must exist in users table) |
| jenis_verifikasi | string | Yes | Verification type (EMAIL/TELEPON/KTP/NPWP) |
| nilai_verifikasi | string | Yes | Verification value (email/phone number/etc.) |
| kode_verifikasi | string | Yes | 6-digit verification code |
| kedaluwarsa_pada | datetime | Yes | Expiration datetime for the code |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Verifikasi berhasil dibuat",
  "data": {...} // Created verification resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/verifications/{id} - Get specific verification

Retrieve a specific verification's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Verification ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data verifikasi berhasil diambil",
  "data": {...} // Verification resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/verifications/{id} - Update verification

Update verification information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Verification ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| jenis_verifikasi | string | Verification type (EMAIL/TELEPON/KTP/NPWP) |
| nilai_verifikasi | string | Verification value (email/phone number/etc.) |
| kode_verifikasi | string | 6-digit verification code |
| kedaluwarsa_pada | datetime | Expiration datetime for the code |
| telah_digunakan | boolean | Whether the code has been used |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Verifikasi berhasil diperbarui",
  "data": {...} // Updated verification resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/verifications/{id} - Delete verification

Delete a specific verification record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Verification ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Verifikasi berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Session Management API

### Session Resource Endpoints

**Base Endpoint**: `/api/sessions`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for user session management.

#### GET /api/sessions - List all sessions

List all sessions with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter sessions by IP or user agent |
| user_id | integer | No | Filter by specific user |
| ip_address | string | No | Filter by IP address |
| sort_by | string | No | Sort by field (last_activity/created_at) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data sesi berhasil diambil",
  "data": [...], // Array of session resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/sessions - Create new session

Create a new session record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id | string | Yes | Session ID (must be unique) |
| id_user | integer | No | User ID (must exist in users table) |
| ip_address | string | No | IP address (max: 45 characters) |
| user_agent | string | No | User agent string |
| payload | string | Yes | Session payload |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Sesi berhasil dibuat",
  "data": {...} // Created session resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/sessions/{id} - Get specific session

Retrieve a specific session's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | string | Session ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data sesi berhasil diambil",
  "data": {...} // Session resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/sessions/{id} - Update session

Update session information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | string | Session ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| ip_address | string | IP address (max: 45 characters) |
| user_agent | string | User agent string |
| payload | string | Session payload |
| last_activity | integer | Last activity timestamp |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Sesi berhasil diperbarui",
  "data": {...} // Updated session resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/sessions/{id} - Delete session

Delete a specific session record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | string | Session ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Sesi berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Address & Location APIs

### Address Resource Endpoints

**Base Endpoint**: `/api/addresses`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for user address management.

#### GET /api/addresses - List all addresses

List all addresses with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| user_id | integer | No | Filter by specific user |
| search | string | No | Search term to filter addresses by name or details |
| is_primary | boolean | No | Filter by primary address status |
| sort_by | string | No | Sort by field (created_at/updated_at/nama_penerima) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data alamat berhasil diambil",
  "data": [...], // Array of address resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/addresses - Create new address

Create a new address record for a user.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_user | integer | Yes | User ID (must exist in users table) |
| label_alamat | string | Yes | Address label (max: 255 characters) |
| nama_penerima | string | Yes | Recipient name (max: 255 characters) |
| nomor_telepon_penerima | string | Yes | Recipient phone number |
| alamat_lengkap | string | Yes | Complete address |
| provinsi_id | integer | Yes | Province ID (must exist in master_provinsi) |
| kota_id | integer | Yes | City ID (must exist in master_kota) |
| kecamatan_id | integer | Yes | District ID (must exist in master_kecamatan) |
| kode_pos_id | integer | Yes | Postal code ID (must exist in master_kode_pos) |
| catatan_pengiriman | string | No | Shipping notes |
| is_primary | boolean | No | Whether this is the primary address |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Alamat berhasil dibuat",
  "data": {...} // Created address resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/addresses/{id} - Get specific address

Retrieve a specific address's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Address ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data alamat berhasil diambil",
  "data": {...} // Address resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/addresses/{id} - Update address

Update address information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Address ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| label_alamat | string | Address label (max: 255 characters) |
| nama_penerima | string | Recipient name (max: 255 characters) |
| nomor_telepon_penerima | string | Recipient phone number |
| alamat_lengkap | string | Complete address |
| provinsi_id | integer | Province ID (must exist in master_provinsi) |
| kota_id | integer | City ID (must exist in master_kota) |
| kecamatan_id | integer | District ID (must exist in master_kecamatan) |
| kode_pos_id | integer | Postal code ID (must exist in master_kode_pos) |
| catatan_pengiriman | string | Shipping notes |
| is_primary | boolean | Whether this is the primary address |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Alamat berhasil diperbarui",
  "data": {...} // Updated address resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/addresses/{id} - Delete address

Delete a specific address record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Address ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Alamat berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Province Resource Endpoints

**Base Endpoint**: `/api/provinces`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for province management.

#### GET /api/provinces - List all provinces

List all provinces with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter provinces by name |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data provinsi berhasil diambil",
  "data": [...], // Array of province resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/provinces - Create new province

Create a new province record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| nama | string | Yes | Province name (must be unique) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Provinsi berhasil dibuat",
  "data": {...} // Created province resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/provinces/{id} - Get specific province

Retrieve a specific province's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Province ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data provinsi berhasil diambil",
  "data": {...} // Province resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/provinces/{id} - Update province

Update province information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Province ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| nama | string | Province name (must be unique) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Provinsi berhasil diperbarui",
  "data": {...} // Updated province resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/provinces/{id} - Delete province

Delete a specific province record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Province ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Provinsi berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### City Resource Endpoints

**Base Endpoint**: `/api/cities`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for city management.

#### GET /api/cities - List all cities

List all cities with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter cities by name |
| provinsi_id | integer | No | Filter by specific province |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data kota berhasil diambil",
  "data": [...], // Array of city resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/cities - Create new city

Create a new city record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| provinsi_id | integer | Yes | Province ID (must exist in master_provinsi) |
| nama | string | Yes | City name |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Kota berhasil dibuat",
  "data": {...} // Created city resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/cities/{id} - Get specific city

Retrieve a specific city's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | City ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data kota berhasil diambil",
  "data": {...} // City resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/cities/{id} - Update city

Update city information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | City ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| provinsi_id | integer | Province ID (must exist in master_provinsi) |
| nama | string | City name |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Kota berhasil diperbarui",
  "data": {...} // Updated city resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/cities/{id} - Delete city

Delete a specific city record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | City ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Kota berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### District Resource Endpoints

**Base Endpoint**: `/api/districts`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for district management.

#### GET /api/districts - List all districts

List all districts with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter districts by name |
| kota_id | integer | No | Filter by specific city |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data kecamatan berhasil diambil",
  "data": [...], // Array of district resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/districts - Create new district

Create a new district record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| kota_id | integer | Yes | City ID (must exist in master_kota) |
| nama | string | Yes | District name |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Kecamatan berhasil dibuat",
  "data": {...} // Created district resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/districts/{id} - Get specific district

Retrieve a specific district's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | District ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data kecamatan berhasil diambil",
  "data": {...} // District resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/districts/{id} - Update district

Update district information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | District ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| kota_id | integer | City ID (must exist in master_kota) |
| nama | string | District name |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Kecamatan berhasil diperbarui",
  "data": {...} // Updated district resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/districts/{id} - Delete district

Delete a specific district record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | District ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Kecamatan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Postal Code Resource Endpoints

**Base Endpoint**: `/api/postal-codes`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for postal code management.

#### GET /api/postal-codes - List all postal codes

List all postal codes with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter postal codes |
| kecamatan_id | integer | No | Filter by specific district |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data kode pos berhasil diambil",
  "data": [...], // Array of postal code resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/postal-codes - Create new postal code

Create a new postal code record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| kecamatan_id | integer | Yes | District ID (must exist in master_kecamatan) |
| kode | string | Yes | Postal code (must be unique) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Kode pos berhasil dibuat",
  "data": {...} // Created postal code resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/postal-codes/{id} - Get specific postal code

Retrieve a specific postal code's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Postal code ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data kode pos berhasil diambil",
  "data": {...} // Postal code resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/postal-codes/{id} - Update postal code

Update postal code information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Postal code ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| kecamatan_id | integer | District ID (must exist in master_kecamatan) |
| kode | string | Postal code (must be unique) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Kode pos berhasil diperbarui",
  "data": {...} // Updated postal code resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/postal-codes/{id} - Delete postal code

Delete a specific postal code record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Postal code ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Kode pos berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Shopping & Order APIs

### Cart Resource Endpoints

**Base Endpoint**: `/api/carts`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for shopping cart management.

#### GET /api/carts - List all carts

List all carts with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| user_id | integer | No | Filter by specific user |
| seller_id | integer | No | Filter by specific seller |
| session_id | string | No | Filter by specific session |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data keranjang belanja berhasil diambil",
  "data": [...], // Array of cart resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/carts - Create new cart

Create a new cart record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_user | integer | No | User ID (must exist in users table) |
| session_id | string | No | Session ID |
| id_seller | integer | Yes | Seller ID (must exist in penjual table) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Keranjang belanja berhasil dibuat",
  "data": {...} // Created cart resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/carts/{id} - Get specific cart

Retrieve a specific cart's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Cart ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data keranjang belanja berhasil diambil",
  "data": {...} // Cart resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/carts/{id} - Update cart

Update cart information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Cart ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| session_id | string | Session ID |
| id_seller | integer | Seller ID (must exist in penjual table) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Keranjang belanja berhasil diperbarui",
  "data": {...} // Updated cart resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/carts/{id} - Delete cart

Delete a specific cart record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Cart ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Keranjang belanja berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Cart Item Resource Endpoints

**Base Endpoint**: `/api/cart-items`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for cart item management.

#### GET /api/cart-items - List all cart items

List all cart items with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| cart_id | integer | No | Filter by specific cart |
| product_id | integer | No | Filter by specific product |
| user_id | integer | No | Filter by specific user |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data item keranjang berhasil diambil",
  "data": [...], // Array of cart item resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/cart-items - Create new cart item

Create a new cart item record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_cart | integer | Yes | Cart ID (must exist in keranjang_belanja table) |
| id_produk | integer | Yes | Product ID (must exist in tb_produk table) |
| id_harga_varian | integer | No | Variant price ID (must exist in harga_varian_produk table) |
| jumlah | integer | Yes | Quantity (min: 1) |
| catatan | string | No | Item notes |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Item keranjang berhasil dibuat",
  "data": {...} // Created cart item resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/cart-items/{id} - Get specific cart item

Retrieve a specific cart item's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Cart item ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data item keranjang berhasil diambil",
  "data": {...} // Cart item resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/cart-items/{id} - Update cart item

Update cart item information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Cart item ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_cart | integer | Cart ID (must exist in keranjang_belanja table) |
| id_produk | integer | Product ID (must exist in tb_produk table) |
| id_harga_varian | integer | Variant price ID (must exist in harga_varian_produk table) |
| jumlah | integer | Quantity (min: 1) |
| catatan | string | Item notes |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Item keranjang berhasil diperbarui",
  "data": {...} // Updated cart item resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/cart-items/{id} - Delete cart item

Delete a specific cart item record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Cart item ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Item keranjang berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Order Resource Endpoints

**Base Endpoint**: `/api/orders`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for order management.

#### GET /api/orders - List all orders

List all orders with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| customer_id | integer | No | Filter by specific customer |
| seller_id | integer | No | Filter by specific seller |
| status | string | No | Filter by order status |
| nomor_pesanan | string | No | Filter by specific order number |
| sort_by | string | No | Sort by field (created_at/updated_at/tanggal_pesanan) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pesanan berhasil diambil",
  "data": [...], // Array of order resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/orders - Create new order

Create a new order record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| nomor_pesanan | string | Yes | Order number (must be unique) |
| id_customer | integer | Yes | Customer ID (must exist in users table) |
| id_alamat_pengiriman | integer | Yes | Shipping address ID (must exist in alamat table) |
| total_harga | number | Yes | Total price |
| jumlah_item | integer | Yes | Number of items in order |
| status_pesanan | string | No | Order status (default: DIPROSES) |
| tanggal_pesanan | datetime | No | Order date (default: now) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Pesanan berhasil dibuat",
  "data": {...} // Created order resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/orders/{id} - Get specific order

Retrieve a specific order's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pesanan berhasil diambil",
  "data": {...} // Order resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/orders/{id} - Update order

Update order information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| nomor_pesanan | string | Order number (must be unique) |
| id_customer | integer | Customer ID (must exist in users table) |
| id_alamat_pengiriman | integer | Shipping address ID (must exist in alamat table) |
| total_harga | number | Total price |
| jumlah_item | integer | Number of items in order |
| status_pesanan | string | Order status |
| tanggal_pesanan | datetime | Order date |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pesanan berhasil diperbarui",
  "data": {...} // Updated order resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/orders/{id} - Delete order

Delete a specific order record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pesanan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Order Item Resource Endpoints

**Base Endpoint**: `/api/order-items`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for order item management.

#### GET /api/order-items - List all order items

List all order items with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| order_id | integer | No | Filter by specific order |
| product_id | integer | No | Filter by specific product |
| seller_id | integer | No | Filter by specific seller |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data item pesanan berhasil diambil",
  "data": [...], // Array of order item resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/order-items - Create new order item

Create a new order item record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_pesanan | integer | Yes | Order ID (must exist in pesanan table) |
| id_seller | integer | Yes | Seller ID (must exist in penjual table) |
| id_produk | integer | Yes | Product ID (must exist in tb_produk table) |
| id_harga_varian | integer | No | Variant price ID (must exist in harga_varian_produk table) |
| jumlah | integer | Yes | Quantity |
| harga_satuan | number | Yes | Unit price |
| subtotal | number | Yes | Subtotal (quantity * unit price) |
| catatan | string | No | Item notes |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Item pesanan berhasil dibuat",
  "data": {...} // Created order item resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/order-items/{id} - Get specific order item

Retrieve a specific order item's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order item ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data item pesanan berhasil diambil",
  "data": {...} // Order item resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/order-items/{id} - Update order item

Update order item information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order item ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_pesanan | integer | Order ID (must exist in pesanan table) |
| id_seller | integer | Seller ID (must exist in penjual table) |
| id_produk | integer | Product ID (must exist in tb_produk table) |
| id_harga_varian | integer | Variant price ID (must exist in harga_varian_produk table) |
| jumlah | integer | Quantity |
| harga_satuan | number | Unit price |
| subtotal | number | Subtotal (quantity * unit price) |
| catatan | string | Item notes |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Item pesanan berhasil diperbarui",
  "data": {...} // Updated order item resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/order-items/{id} - Delete order item

Delete a specific order item record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order item ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Item pesanan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Order Status History Resource Endpoints

**Base Endpoint**: `/api/order-status-history`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for order status history management.

#### GET /api/order-status-history - List all order status histories

List all order status histories with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| order_id | integer | No | Filter by specific order |
| status_sebelumnya | string | No | Filter by previous status |
| status_sekarang | string | No | Filter by current status |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data riwayat status pesanan berhasil diambil",
  "data": [...], // Array of order status history resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/order-status-history - Create new order status history

Create a new order status history record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_pesanan | integer | Yes | Order ID (must exist in pesanan table) |
| status_sebelumnya | string | No | Previous status |
| status_sekarang | string | Yes | Current status |
| catatan_perubahan | string | No | Change notes |
| dibuat_oleh | string | No | Created by (SYSTEM/ADMIN/SELLER/CUSTOMER) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Riwayat status pesanan berhasil dibuat",
  "data": {...} // Created order status history resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/order-status-history/{id} - Get specific order status history

Retrieve a specific order status history's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order status history ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data riwayat status pesanan berhasil diambil",
  "data": {...} // Order status history resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/order-status-history/{id} - Update order status history

Update order status history information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order status history ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_pesanan | integer | Order ID (must exist in pesanan table) |
| status_sebelumnya | string | Previous status |
| status_sekarang | string | Current status |
| catatan_perubahan | string | Change notes |
| dibuat_oleh | string | Created by (SYSTEM/ADMIN/SELLER/CUSTOMER) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Riwayat status pesanan berhasil diperbarui",
  "data": {...} // Updated order status history resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/order-status-history/{id} - Delete order status history

Delete a specific order status history record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order status history ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Riwayat status pesanan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Payment & Shipping APIs

### Shipping Method Resource Endpoints

**Base Endpoint**: `/api/shipping-methods`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for shipping method management.

#### GET /api/shipping-methods - List all shipping methods

List all shipping methods with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter shipping methods |
| tipe_layanan | string | No | Filter by service type |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data metode pengiriman berhasil diambil",
  "data": [...], // Array of shipping method resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/shipping-methods - Create new shipping method

Create a new shipping method record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| nama_kurir | string | Yes | Courier name (max: 255 characters) |
| tipe_layanan | string | Yes | Service type |
| deskripsi_layanan | string | No | Service description |
| biaya_dasar | number | No | Base cost |
| estimasi_pengiriman | string | No | Delivery time estimate |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Metode pengiriman berhasil dibuat",
  "data": {...} // Created shipping method resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/shipping-methods/{id} - Get specific shipping method

Retrieve a specific shipping method's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Shipping method ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data metode pengiriman berhasil diambil",
  "data": {...} // Shipping method resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/shipping-methods/{id} - Update shipping method

Update shipping method information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Shipping method ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| nama_kurir | string | Courier name (max: 255 characters) |
| tipe_layanan | string | Service type |
| deskripsi_layanan | string | Service description |
| biaya_dasar | number | Base cost |
| estimasi_pengiriman | string | Delivery time estimate |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Metode pengiriman berhasil diperbarui",
  "data": {...} // Updated shipping method resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/shipping-methods/{id} - Delete shipping method

Delete a specific shipping method record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Shipping method ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Metode pengiriman berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Shipping Rate Resource Endpoints

**Base Endpoint**: `/api/shipping-rates`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for shipping rate management.

#### GET /api/shipping-rates - List all shipping rates

List all shipping rates with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| kurir_id | integer | No | Filter by specific courier |
| kota_asal_id | integer | No | Filter by specific origin city |
| kota_tujuan_id | integer | No | Filter by specific destination city |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data tarif pengiriman berhasil diambil",
  "data": [...], // Array of shipping rate resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/shipping-rates - Create new shipping rate

Create a new shipping rate record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_kurir | integer | Yes | Courier ID (must exist in metode_pengiriman table) |
| id_kota_asal | integer | Yes | Origin city ID (must exist in master_kota table) |
| id_kota_tujuan | integer | Yes | Destination city ID (must exist in master_kota table) |
| biaya_pengiriman | number | Yes | Shipping cost |
| estimasi_hari | integer | Yes | Estimated days for delivery |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Tarif pengiriman berhasil dibuat",
  "data": {...} // Created shipping rate resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/shipping-rates/{id} - Get specific shipping rate

Retrieve a specific shipping rate's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Shipping rate ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data tarif pengiriman berhasil diambil",
  "data": {...} // Shipping rate resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/shipping-rates/{id} - Update shipping rate

Update shipping rate information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Shipping rate ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_kurir | integer | Courier ID (must exist in metode_pengiriman table) |
| id_kota_asal | integer | Origin city ID (must exist in master_kota table) |
| id_kota_tujuan | integer | Destination city ID (must exist in master_kota table) |
| biaya_pengiriman | number | Shipping cost |
| estimasi_hari | integer | Estimated days for delivery |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Tarif pengiriman berhasil diperbarui",
  "data": {...} // Updated shipping rate resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/shipping-rates/{id} - Delete shipping rate

Delete a specific shipping rate record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Shipping rate ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Tarif pengiriman berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Order Shipping Resource Endpoints

**Base Endpoint**: `/api/order-shipping`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for order shipping management.

#### GET /api/order-shipping - List all order shipping records

List all order shipping records with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| order_id | integer | No | Filter by specific order |
| kurir_id | integer | No | Filter by specific courier |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pengiriman pesanan berhasil diambil",
  "data": [...], // Array of order shipping resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/order-shipping - Create new order shipping

Create a new order shipping record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_pesanan | integer | Yes | Order ID (must exist in pesanan table) |
| id_kurir | integer | Yes | Courier ID (must exist in metode_pengiriman table) |
| nama_kurir | string | Yes | Courier name (max: 255 characters) |
| layanan_kurir | string | No | Courier service type |
| biaya_pengiriman | number | Yes | Shipping cost |
| estimasi_pengiriman | string | No | Delivery time estimate |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Pengiriman pesanan berhasil dibuat",
  "data": {...} // Created order shipping resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/order-shipping/{id} - Get specific order shipping

Retrieve a specific order shipping's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order shipping ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pengiriman pesanan berhasil diambil",
  "data": {...} // Order shipping resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/order-shipping/{id} - Update order shipping

Update order shipping information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order shipping ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_pesanan | integer | Order ID (must exist in pesanan table) |
| id_kurir | integer | Courier ID (must exist in metode_pengiriman table) |
| nama_kurir | string | Courier name (max: 255 characters) |
| layanan_kurir | string | Courier service type |
| biaya_pengiriman | number | Shipping cost |
| estimasi_pengiriman | string | Delivery time estimate |
| nomor_resi | string | Shipping receipt number |
| status_pengiriman | string | Shipping status |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pengiriman pesanan berhasil diperbarui",
  "data": {...} // Updated order shipping resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/order-shipping/{id} - Delete order shipping

Delete a specific order shipping record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Order shipping ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pengiriman pesanan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Payment Method Resource Endpoints

**Base Endpoint**: `/api/payment-methods`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for payment method management.

#### GET /api/payment-methods - List all payment methods

List all payment methods with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter payment methods |
| tipe_pembayaran | string | No | Filter by payment type |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data metode pembayaran berhasil diambil",
  "data": [...], // Array of payment method resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/payment-methods - Create new payment method

Create a new payment method record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| nama_pembayaran | string | Yes | Payment method name (max: 255 characters) |
| tipe_pembayaran | string | Yes | Payment type (TRANSFER_BANK/E_WALLET/VIRTUAL_ACCOUNT/CREDIT_CARD/COD/QRIS) |
| provider_pembayaran | string | Yes | Payment provider name (max: 255 characters) |
| deskripsi_pembayaran | string | No | Payment method description |
| biaya_transaksi | number | No | Transaction fee |
| status_pembayaran | string | No | Payment method status (AKTIF/TIDAK_AKTIF) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Metode pembayaran berhasil dibuat",
  "data": {...} // Created payment method resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/payment-methods/{id} - Get specific payment method

Retrieve a specific payment method's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment method ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data metode pembayaran berhasil diambil",
  "data": {...} // Payment method resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/payment-methods/{id} - Update payment method

Update payment method information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment method ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| nama_pembayaran | string | Payment method name (max: 255 characters) |
| tipe_pembayaran | string | Payment type (TRANSFER_BANK/E_WALLET/VIRTUAL_ACCOUNT/CREDIT_CARD/COD/QRIS) |
| provider_pembayaran | string | Payment provider name (max: 255 characters) |
| deskripsi_pembayaran | string | Payment method description |
| biaya_transaksi | number | Transaction fee |
| status_pembayaran | string | Payment method status (AKTIF/TIDAK_AKTIF) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Metode pembayaran berhasil diperbarui",
  "data": {...} // Updated payment method resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/payment-methods/{id} - Delete payment method

Delete a specific payment method record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment method ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Metode pembayaran berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Payment Transaction Resource Endpoints

**Base Endpoint**: `/api/payment-transactions`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for payment transaction management.

#### GET /api/payment-transactions - List all payment transactions

List all payment transactions with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| order_id | integer | No | Filter by specific order |
| payment_method_id | integer | No | Filter by specific payment method |
| status_transaksi | string | No | Filter by transaction status |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data transaksi pembayaran berhasil diambil",
  "data": [...], // Array of payment transaction resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/payment-transactions - Create new payment transaction

Create a new payment transaction record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_pesanan | integer | Yes | Order ID (must exist in pesanan table) |
| id_metode_pembayaran | integer | Yes | Payment method ID (must exist in metode_pembayaran table) |
| referensi_id | string | Yes | Reference ID (must be unique) |
| jumlah_pembayaran | number | Yes | Payment amount |
| status_transaksi | string | No | Transaction status (default: PENDING) |
| tanggal_transaksi | datetime | No | Transaction date (default: now) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Transaksi pembayaran berhasil dibuat",
  "data": {...} // Created payment transaction resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/payment-transactions/{id} - Get specific payment transaction

Retrieve a specific payment transaction's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment transaction ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data transaksi pembayaran berhasil diambil",
  "data": {...} // Payment transaction resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/payment-transactions/{id} - Update payment transaction

Update payment transaction information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment transaction ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_pesanan | integer | Order ID (must exist in pesanan table) |
| id_metode_pembayaran | integer | Payment method ID (must exist in metode_pembayaran table) |
| referensi_id | string | Reference ID (must be unique) |
| jumlah_pembayaran | number | Payment amount |
| status_transaksi | string | Transaction status |
| tanggal_transaksi | datetime | Transaction date |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Transaksi pembayaran berhasil diperbarui",
  "data": {...} // Updated payment transaction resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/payment-transactions/{id} - Delete payment transaction

Delete a specific payment transaction record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment transaction ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Transaksi pembayaran berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Payment Log Resource Endpoints

**Base Endpoint**: `/api/payment-logs`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for payment log management.

#### GET /api/payment-logs - List all payment logs

List all payment logs with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| transaction_id | integer | No | Filter by specific transaction |
| tipe_log | string | No | Filter by log type |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data log pembayaran berhasil diambil",
  "data": [...], // Array of payment log resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/payment-logs - Create new payment log

Create a new payment log record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_transaksi_pembayaran | integer | Yes | Payment transaction ID (must exist in transaksi_pembayaran table) |
| tipe_log | string | Yes | Log type (REQUEST/RESPONSE/CALLBACK/ERROR) |
| konten_log | string | Yes | Log content |
| timestamp_log | datetime | No | Log timestamp (default: now) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Log pembayaran berhasil dibuat",
  "data": {...} // Created payment log resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/payment-logs/{id} - Get specific payment log

Retrieve a specific payment log's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment log ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data log pembayaran berhasil diambil",
  "data": {...} // Payment log resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/payment-logs/{id} - Update payment log

Update payment log information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment log ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_transaksi_pembayaran | integer | Payment transaction ID (must exist in transaksi_pembayaran table) |
| tipe_log | string | Log type (REQUEST/RESPONSE/CALLBACK/ERROR) |
| konten_log | string | Log content |
| timestamp_log | datetime | Log timestamp |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Log pembayaran berhasil diperbarui",
  "data": {...} // Updated payment log resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/payment-logs/{id} - Delete payment log

Delete a specific payment log record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Payment log ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Log pembayaran berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Review & Feedback APIs

### Product Review Resource Endpoints

**Base Endpoint**: `/api/product-reviews`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for product review management.

#### GET /api/product-reviews - List all product reviews

List all product reviews with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| product_id | integer | No | Filter by specific product |
| seller_id | integer | No | Filter by specific seller |
| buyer_id | integer | No | Filter by specific buyer |
| rating | integer | No | Filter by rating value |
| sort_by | string | No | Sort by field (created_at/updated_at/rating) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data ulasan produk berhasil diambil",
  "data": [...], // Array of review resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/product-reviews - Create new product review

Create a new product review.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_produk | integer | Yes | Product ID (must exist in tb_produk table) |
| id_harga_varian | integer | No | Variant price ID (must exist in harga_varian_produk table) |
| id_pembeli | integer | Yes | Buyer ID (must exist in users table) |
| rating | integer | Yes | Rating value (1-5) |
| komentar | string | No | Review comment |
| judul | string | No | Review title |
| status_ulasan | string | No | Review status (default: AKTIF) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Ulasan produk berhasil dibuat",
  "data": {...} // Created review resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/product-reviews/{id} - Get specific product review

Retrieve a specific product review's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data ulasan produk berhasil diambil",
  "data": {...} // Review resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/product-reviews/{id} - Update product review

Update product review information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_produk | integer | Product ID (must exist in tb_produk table) |
| id_harga_varian | integer | Variant price ID (must exist in harga_varian_produk table) |
| id_pembeli | integer | Buyer ID (must exist in users table) |
| rating | integer | Rating value (1-5) |
| komentar | string | Review comment |
| judul | string | Review title |
| status_ulasan | string | Review status |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Ulasan produk berhasil diperbarui",
  "data": {...} // Updated review resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/product-reviews/{id} - Delete product review

Delete a specific product review record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Ulasan produk berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Review Media Resource Endpoints

**Base Endpoint**: `/api/review-media`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for review media management.

#### GET /api/review-media - List all review media

List all review media with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| review_id | integer | No | Filter by specific review |
| tipe_media | string | No | Filter by media type |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data media ulasan berhasil diambil",
  "data": [...], // Array of review media resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/review-media - Create new review media

Create a new review media record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_review | integer | Yes | Review ID (must exist in ulasan_produk table) |
| tipe_media | string | Yes | Media type (GAMBAR/VIDEO) |
| url_media | string | Yes | Media URL (max: 2048 characters) |
| urutan | integer | No | Display order (default: 0) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Media ulasan berhasil dibuat",
  "data": {...} // Created review media resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/review-media/{id} - Get specific review media

Retrieve a specific review media's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review media ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data media ulasan berhasil diambil",
  "data": {...} // Review media resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/review-media/{id} - Update review media

Update review media information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review media ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_review | integer | Review ID (must exist in ulasan_produk table) |
| tipe_media | string | Media type (GAMBAR/VIDEO) |
| url_media | string | Media URL (max: 2048 characters) |
| urutan | integer | Display order |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Media ulasan berhasil diperbarui",
  "data": {...} // Updated review media resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/review-media/{id} - Delete review media

Delete a specific review media record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review media ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Media ulasan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Review Vote Resource Endpoints

**Base Endpoint**: `/api/review-votes`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for review vote management.

#### GET /api/review-votes - List all review votes

List all review votes with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| review_id | integer | No | Filter by specific review |
| user_id | integer | No | Filter by specific user |
| tipe_vote | string | No | Filter by vote type |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data suara ulasan berhasil diambil",
  "data": [...], // Array of review vote resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/review-votes - Create new review vote

Create a new review vote record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_review | integer | Yes | Review ID (must exist in ulasan_produk table) |
| id_user | integer | Yes | User ID (must exist in users table) |
| tipe_vote | string | Yes | Vote type (SUKA/TIDAK_SUKA) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Suara ulasan berhasil dibuat",
  "data": {...} // Created review vote resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/review-votes/{id} - Get specific review vote

Retrieve a specific review vote's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review vote ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data suara ulasan berhasil diambil",
  "data": {...} // Review vote resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/review-votes/{id} - Update review vote

Update review vote information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review vote ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_review | integer | Review ID (must exist in ulasan_produk table) |
| id_user | integer | User ID (must exist in users table) |
| tipe_vote | string | Vote type (SUKA/TIDAK_SUKA) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Suara ulasan berhasil diperbarui",
  "data": {...} // Updated review vote resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/review-votes/{id} - Delete review vote

Delete a specific review vote record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Review vote ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Suara ulasan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Notification & Activity APIs

### Notification Resource Endpoints

**Base Endpoint**: `/api/notifications`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for user notification management.

#### GET /api/notifications - List all notifications

List all notifications with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| user_id | integer | No | Filter by specific user |
| tipe_notifikasi | string | No | Filter by notification type |
| status_dibaca | boolean | No | Filter by read status |
| sort_by | string | No | Sort by field (created_at/updated_at) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data notifikasi berhasil diambil",
  "data": [...], // Array of notification resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/notifications - Create new notification

Create a new notification record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_user | integer | Yes | User ID (must exist in users table) |
| tipe_notifikasi | string | Yes | Notification type (PESANAN/PEMBAYARAN/PENGIRIMAN/ULASAN/SYSTEM/CHAT) |
| judul_notifikasi | string | Yes | Notification title (max: 255 characters) |
| isi_notifikasi | string | Yes | Notification content |
| link_notifikasi | string | No | Notification link |
| status_dibaca | boolean | No | Whether notification has been read (default: false) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Notifikasi berhasil dibuat",
  "data": {...} // Created notification resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/notifications/{id} - Get specific notification

Retrieve a specific notification's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Notification ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data notifikasi berhasil diambil",
  "data": {...} // Notification resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/notifications/{id} - Update notification

Update notification information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Notification ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| tipe_notifikasi | string | Notification type (PESANAN/PEMBAYARAN/PENGIRIMAN/ULASAN/SYSTEM/CHAT) |
| judul_notifikasi | string | Notification title (max: 255 characters) |
| isi_notifikasi | string | Notification content |
| link_notifikasi | string | Notification link |
| status_dibaca | boolean | Whether notification has been read |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Notifikasi berhasil diperbarui",
  "data": {...} // Updated notification resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/notifications/{id} - Delete notification

Delete a specific notification record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Notification ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Notifikasi berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### User Activity Resource Endpoints

**Base Endpoint**: `/api/activities`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for user activity management.

#### GET /api/activities - List all activities

List all activities with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| user_id | integer | No | Filter by specific user |
| tipe_aktivitas | string | No | Filter by activity type |
| tanggal_mulai | date | No | Filter by start date |
| tanggal_selesai | date | No | Filter by end date |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data aktivitas berhasil diambil",
  "data": [...], // Array of activity resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/activities - Create new activity

Create a new activity record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_user | integer | Yes | User ID (must exist in users table) |
| sesi_id | string | No | Session ID (max: 255 characters) |
| tipe_aktivitas | string | Yes | Activity type (LOGIN/LOGOUT/VIEW_PRODUK/CARI_PRODUK/TAMBAH_KERANJANG/CHECKOUT/PAYMENT/REVIEW/CHAT) |
| deskripsi_aktivitas | string | No | Activity description |
| ip_address | string | No | IP address of the activity |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Aktivitas berhasil dibuat",
  "data": {...} // Created activity resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/activities/{id} - Get specific activity

Retrieve a specific activity's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Activity ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data aktivitas berhasil diambil",
  "data": {...} // Activity resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/activities/{id} - Update activity

Update activity information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Activity ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| sesi_id | string | Session ID (max: 255 characters) |
| tipe_aktivitas | string | Activity type (LOGIN/LOGOUT/VIEW_PRODUK/CARI_PRODUK/TAMBAH_KERANJANG/CHECKOUT/PAYMENT/REVIEW/CHAT) |
| deskripsi_aktivitas | string | Activity description |
| ip_address | string | IP address of the activity |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Aktivitas berhasil diperbarui",
  "data": {...} // Updated activity resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/activities/{id} - Delete activity

Delete a specific activity record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Activity ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Aktivitas berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Search History Resource Endpoints

**Base Endpoint**: `/api/search-history`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for search history management.

#### GET /api/search-history - List all search histories

List all search histories with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| user_id | integer | No | Filter by specific user |
| kata_pencarian | string | No | Filter by search term |
| tanggal_mulai | date | No | Filter by start date |
| tanggal_selesai | date | No | Filter by end date |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data riwayat pencarian berhasil diambil",
  "data": [...], // Array of search history resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/search-history - Create new search history

Create a new search history record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_user | integer | Yes | User ID (must exist in users table) |
| kata_pencarian | string | Yes | Search term (max: 255 characters) |
| jumlah_hasil | integer | Yes | Number of search results (min: 0) |
| tipe_pencarian | string | No | Search type (PRODUK/PELANGGAN/PELANGGAN_LAINNYA) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Riwayat pencarian berhasil dibuat",
  "data": {...} // Created search history resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/search-history/{id} - Get specific search history

Retrieve a specific search history's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Search history ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data riwayat pencarian berhasil diambil",
  "data": {...} // Search history resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/search-history/{id} - Update search history

Update search history information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Search history ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| kata_pencarian | string | Search term (max: 255 characters) |
| jumlah_hasil | integer | Number of search results (min: 0) |
| tipe_pencarian | string | Search type (PRODUK/PELANGGAN/PELANGGAN_LAINNYA) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Riwayat pencarian berhasil diperbarui",
  "data": {...} // Updated search history resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/search-history/{id} - Delete search history

Delete a specific search history record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Search history ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Riwayat pencarian berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Admin & Reporting APIs

### Admin User Resource Endpoints

**Base Endpoint**: `/api/admin-users`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for admin user management.

#### GET /api/admin-users - List all admin users

List all admin users with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter admin users |
| role_admin | string | No | Filter by admin role |
| sort_by | string | No | Sort by field (created_at/updated_at) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pengguna admin berhasil diambil",
  "data": [...], // Array of admin user resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/admin-users - Create new admin user

Create a new admin user record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_user | integer | Yes | User ID (must exist in users table) |
| role_admin | string | Yes | Admin role (SUPER_ADMIN/ADMIN_CONTENT/ADMIN_FINANCE/ADMIN_CUSTOMER/ADMIN_LOGISTIC) |
| permissions | json | No | Admin permissions |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Pengguna admin berhasil dibuat",
  "data": {...} // Created admin user resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/admin-users/{id} - Get specific admin user

Retrieve a specific admin user's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Admin user ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pengguna admin berhasil diambil",
  "data": {...} // Admin user resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/admin-users/{id} - Update admin user

Update admin user information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Admin user ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_user | integer | User ID (must exist in users table) |
| role_admin | string | Admin role (SUPER_ADMIN/ADMIN_CONTENT/ADMIN_FINANCE/ADMIN_CUSTOMER/ADMIN_LOGISTIC) |
| permissions | json | Admin permissions |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pengguna admin berhasil diperbarui",
  "data": {...} // Updated admin user resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/admin-users/{id} - Delete admin user

Delete a specific admin user record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Admin user ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pengguna admin berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Seller Report Resource Endpoints

**Base Endpoint**: `/api/seller-reports`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for seller report management.

#### GET /api/seller-reports - List all seller reports

List all seller reports with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| seller_id | integer | No | Filter by specific seller |
| tipe_laporan | string | No | Filter by report type |
| periode_laporan | date | No | Filter by report period |
| sort_by | string | No | Sort by field (created_at/updated_at/periode_laporan) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data laporan penjual berhasil diambil",
  "data": [...], // Array of seller report resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/seller-reports - Create new seller report

Create a new seller report record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id_seller | integer | Yes | Seller ID (must exist in penjual table) |
| tipe_laporan | string | Yes | Report type (HARIAN/MINGGUAN/BULANAN/TAHUNAN) |
| periode_laporan | date | Yes | Report period |
| data_laporan | json | No | Report data |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Laporan penjual berhasil dibuat",
  "data": {...} // Created seller report resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/seller-reports/{id} - Get specific seller report

Retrieve a specific seller report's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Seller report ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data laporan penjual berhasil diambil",
  "data": {...} // Seller report resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/seller-reports/{id} - Update seller report

Update seller report information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Seller report ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| id_seller | integer | Seller ID (must exist in penjual table) |
| tipe_laporan | string | Report type (HARIAN/MINGGUAN/BULANAN/TAHUNAN) |
| periode_laporan | date | Report period |
| data_laporan | json | Report data |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Laporan penjual berhasil diperbarui",
  "data": {...} // Updated seller report resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/seller-reports/{id} - Delete seller report

Delete a specific seller report record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Seller report ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Laporan penjual berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Sales Report Resource Endpoints

**Base Endpoint**: `/api/sales-reports`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for sales report management.

#### GET /api/sales-reports - List all sales reports

List all sales reports with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| tipe_laporan | string | No | Filter by report type |
| periode_laporan | date | No | Filter by report period |
| sort_by | string | No | Sort by field (created_at/updated_at/periode_laporan) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data laporan penjualan berhasil diambil",
  "data": [...], // Array of sales report resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/sales-reports - Create new sales report

Create a new sales report record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| tipe_laporan | string | Yes | Report type (GLOBAL/KATEGORI/PROVINSI/METODE_PEMBAYARAN) |
| periode_laporan | date | Yes | Report period |
| data_laporan | json | No | Report data |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Laporan penjualan berhasil dibuat",
  "data": {...} // Created sales report resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/sales-reports/{id} - Get specific sales report

Retrieve a specific sales report's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Sales report ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data laporan penjualan berhasil diambil",
  "data": {...} // Sales report resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/sales-reports/{id} - Update sales report

Update sales report information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Sales report ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| tipe_laporan | string | Report type (GLOBAL/KATEGORI/PROVINSI/METODE_PEMBAYARAN) |
| periode_laporan | date | Report period |
| data_laporan | json | Report data |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Laporan penjualan berhasil diperbarui",
  "data": {...} // Updated sales report resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/sales-reports/{id} - Delete sales report

Delete a specific sales report record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Sales report ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Laporan penjualan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Chat System APIs

### Chat Conversation Resource Endpoints

**Base Endpoint**: `/api/chat-conversations`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for chat conversation management.

#### GET /api/chat-conversations - List all chat conversations

List all chat conversations with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| tipe | string | No | Filter by conversation type |
| owner_user_id | integer | No | Filter by conversation owner |
| sort_by | string | No | Sort by field (created_at/updated_at) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data percakapan chat berhasil diambil",
  "data": [...], // Array of chat conversation resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/chat-conversations - Create new chat conversation

Create a new chat conversation record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| tipe | string | Yes | Conversation type (PRIVATE/GROUP/ORDER_SUPPORT,PRODUCT_SUPPORT,SYSTEM) |
| judul | string | No | Conversation title (max: 512 characters) |
| owner_user_id | integer | No | Owner user ID (must exist in users table) |
| deskripsi | string | No | Conversation description |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Percakapan chat berhasil dibuat",
  "data": {...} // Created chat conversation resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/chat-conversations/{id} - Get specific chat conversation

Retrieve a specific chat conversation's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat conversation ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data percakapan chat berhasil diambil",
  "data": {...} // Chat conversation resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/chat-conversations/{id} - Update chat conversation

Update chat conversation information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat conversation ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| tipe | string | Conversation type (PRIVATE/GROUP/ORDER_SUPPORT,PRODUCT_SUPPORT,SYSTEM) |
| judul | string | Conversation title (max: 512 characters) |
| owner_user_id | integer | Owner user ID (must exist in users table) |
| deskripsi | string | Conversation description |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Percakapan chat berhasil diperbarui",
  "data": {...} // Updated chat conversation resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/chat-conversations/{id} - Delete chat conversation

Delete a specific chat conversation record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat conversation ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Percakapan chat berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Chat Participant Resource Endpoints

**Base Endpoint**: `/api/chat-participants`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for chat participant management.

#### GET /api/chat-participants - List all chat participants

List all chat participants with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| percakapan_id | integer | No | Filter by specific conversation |
| user_id | integer | No | Filter by specific user |
| shop_profile_id | integer | No | Filter by specific shop profile |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data peserta chat berhasil diambil",
  "data": [...], // Array of chat participant resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/chat-participants - Create new chat participant

Create a new chat participant record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| percakapan_id | integer | Yes | Conversation ID (must exist in chat_percakapan table) |
| user_id | integer | No | User ID (must exist in users table) |
| shop_profile_id | integer | No | Shop profile ID (must exist in penjual table) |
| status_partisipan | string | No | Participant status (AKTIF/BLOCKED) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Peserta chat berhasil dibuat",
  "data": {...} // Created chat participant resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/chat-participants/{id} - Get specific chat participant

Retrieve a specific chat participant's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat participant ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data peserta chat berhasil diambil",
  "data": {...} // Chat participant resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/chat-participants/{id} - Update chat participant

Update chat participant information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat participant ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| percakapan_id | integer | Conversation ID (must exist in chat_percakapan table) |
| user_id | integer | User ID (must exist in users table) |
| shop_profile_id | integer | Shop profile ID (must exist in penjual table) |
| status_partisipan | string | Participant status (AKTIF/BLOCKED) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Peserta chat berhasil diperbarui",
  "data": {...} // Updated chat participant resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/chat-participants/{id} - Delete chat participant

Delete a specific chat participant record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat participant ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Peserta chat berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Chat Message Resource Endpoints

**Base Endpoint**: `/api/chat-messages`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for chat message management.

#### GET /api/chat-messages - List all chat messages

List all chat messages with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| percakapan_id | integer | No | Filter by specific conversation |
| pengirim_user_id | integer | No | Filter by specific sender |
| pengirim_shop_profile_id | integer | No | Filter by specific shop sender |
| sort_by | string | No | Sort by field (created_at/updated_at) |
| sort_order | string | No | Sort order (asc/desc) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pesan chat berhasil diambil",
  "data": [...], // Array of chat message resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/chat-messages - Create new chat message

Create a new chat message record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| percakapan_id | integer | Yes | Conversation ID (must exist in chat_percakapan table) |
| pengirim_user_id | integer | No | Sender user ID (must exist in users table) |
| pengirim_shop_profile_id | integer | No | Sender shop profile ID (must exist in penjual table) |
| isi_pesan | string | Yes | Message content |
| tipe_pesan | string | No | Message type (TEXT/IMAGE/AUDIO/VIDEO/DOCUMENT/FILE/LINK) |
| status_pesan | string | No | Message status (TERKIRIM/DITERIMA/DIBACA/GAGAL) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Pesan chat berhasil dibuat",
  "data": {...} // Created chat message resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/chat-messages/{id} - Get specific chat message

Retrieve a specific chat message's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat message ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pesan chat berhasil diambil",
  "data": {...} // Chat message resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/chat-messages/{id} - Update chat message

Update chat message information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat message ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| percakapan_id | integer | Conversation ID (must exist in chat_percakapan table) |
| pengirim_user_id | integer | Sender user ID (must exist in users table) |
| pengirim_shop_profile_id | integer | Sender shop profile ID (must exist in penjual table) |
| isi_pesan | string | Message content |
| tipe_pesan | string | Message type (TEXT/IMAGE/AUDIO/VIDEO/DOCUMENT/FILE/LINK) |
| status_pesan | string | Message status (TERKIRIM/DITERIMA/DIBACA/GAGAL) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pesan chat berhasil diperbarui",
  "data": {...} // Updated chat message resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/chat-messages/{id} - Delete chat message

Delete a specific chat message record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat message ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pesan chat berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Message Status Resource Endpoints

**Base Endpoint**: `/api/message-statuses`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for message status management.

#### GET /api/message-statuses - List all message statuses

List all message statuses with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| pesan_id | integer | No | Filter by specific message |
| user_id | integer | No | Filter by specific user |
| status | string | No | Filter by status |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data status pesan berhasil diambil",
  "data": [...], // Array of message status resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/message-statuses - Create new message status

Create a new message status record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| pesan_id | integer | Yes | Message ID (must exist in chat_pesan_chat table) |
| user_id | integer | Yes | User ID (must exist in users table) |
| status | string | Yes | Status (TERKIRIM/DITERIMA/DIBACA/GAGAL) |
| timestamp_status | datetime | No | Status timestamp (default: now) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Status pesan berhasil dibuat",
  "data": {...} // Created message status resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/message-statuses/{id} - Get specific message status

Retrieve a specific message status's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message status ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data status pesan berhasil diambil",
  "data": {...} // Message status resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/message-statuses/{id} - Update message status

Update message status information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message status ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| pesan_id | integer | Message ID (must exist in chat_pesan_chat table) |
| user_id | integer | User ID (must exist in users table) |
| status | string | Status (TERKIRIM/DITERIMA/DIBACA/GAGAL) |
| timestamp_status | datetime | Status timestamp |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Status pesan berhasil diperbarui",
  "data": {...} // Updated message status resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/message-statuses/{id} - Delete message status

Delete a specific message status record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message status ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Status pesan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Message Reaction Resource Endpoints

**Base Endpoint**: `/api/message-reactions`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for message reaction management.

#### GET /api/message-reactions - List all message reactions

List all message reactions with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| pesan_id | integer | No | Filter by specific message |
| user_id | integer | No | Filter by specific user |
| reaksi | string | No | Filter by reaction |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data reaksi pesan berhasil diambil",
  "data": [...], // Array of message reaction resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/message-reactions - Create new message reaction

Create a new message reaction record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| pesan_id | integer | Yes | Message ID (must exist in chat_pesan_chat table) |
| user_id | integer | Yes | User ID (must exist in users table) |
| reaksi | string | Yes | Reaction (max: 10 characters) |
| timestamp_reaksi | datetime | No | Reaction timestamp (default: now) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Reaksi pesan berhasil dibuat",
  "data": {...} // Created message reaction resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/message-reactions/{id} - Get specific message reaction

Retrieve a specific message reaction's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message reaction ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data reaksi pesan berhasil diambil",
  "data": {...} // Message reaction resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/message-reactions/{id} - Update message reaction

Update message reaction information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message reaction ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| pesan_id | integer | Message ID (must exist in chat_pesan_chat table) |
| user_id | integer | User ID (must exist in users table) |
| reaksi | string | Reaction (max: 10 characters) |
| timestamp_reaksi | datetime | Reaction timestamp |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Reaksi pesan berhasil diperbarui",
  "data": {...} // Updated message reaction resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/message-reactions/{id} - Delete message reaction

Delete a specific message reaction record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message reaction ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Reaksi pesan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Message Edit Resource Endpoints

**Base Endpoint**: `/api/message-edits`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for message edit management.

#### GET /api/message-edits - List all message edits

List all message edits with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| pesan_id | integer | No | Filter by specific message |
| editor_id | integer | No | Filter by specific editor |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data edit pesan berhasil diambil",
  "data": [...], // Array of message edit resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/message-edits - Create new message edit

Create a new message edit record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| pesan_id | integer | Yes | Message ID (must exist in chat_pesan_chat table) |
| editor_id | integer | Yes | Editor ID (must exist in users table) |
| isi_sebelumnya | string | Yes | Previous content (max: 1000 characters) |
| isi_baru | string | Yes | New content (max: 1000 characters) |
| timestamp_edit | datetime | No | Edit timestamp (default: now) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Edit pesan berhasil dibuat",
  "data": {...} // Created message edit resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/message-edits/{id} - Get specific message edit

Retrieve a specific message edit's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message edit ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data edit pesan berhasil diambil",
  "data": {...} // Message edit resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/message-edits/{id} - Update message edit

Update message edit information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message edit ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| pesan_id | integer | Message ID (must exist in chat_pesan_chat table) |
| editor_id | integer | Editor ID (must exist in users table) |
| isi_sebelumnya | string | Previous content (max: 1000 characters) |
| isi_baru | string | New content (max: 1000 characters) |
| timestamp_edit | datetime | Edit timestamp |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Edit pesan berhasil diperbarui",
  "data": {...} // Updated message edit resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/message-edits/{id} - Delete message edit

Delete a specific message edit record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message edit ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Edit pesan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Message Attachment Resource Endpoints

**Base Endpoint**: `/api/message-attachments`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for message attachment management.

#### GET /api/message-attachments - List all message attachments

List all message attachments with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| pesan_id | integer | No | Filter by specific message |
| tipe_lampiran | string | No | Filter by attachment type |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data lampiran pesan berhasil diambil",
  "data": [...], // Array of message attachment resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/message-attachments - Create new message attachment

Create a new message attachment record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| pesan_id | integer | Yes | Message ID (must exist in chat_pesan_chat table) |
| tipe_lampiran | string | Yes | Attachment type (IMAGE/VIDEO/AUDIO/DOCUMENT/FILE) |
| url_lampiran | string | Yes | Attachment URL (max: 2048 characters) |
| ukuran_lampiran | integer | No | Attachment size in bytes |
| nama_file | string | No | File name (max: 255 characters) |
| mime_type | string | No | MIME type (max: 255 characters) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Lampiran pesan berhasil dibuat",
  "data": {...} // Created message attachment resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/message-attachments/{id} - Get specific message attachment

Retrieve a specific message attachment's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message attachment ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data lampiran pesan berhasil diambil",
  "data": {...} // Message attachment resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/message-attachments/{id} - Update message attachment

Update message attachment information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message attachment ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| pesan_id | integer | Message ID (must exist in chat_pesan_chat table) |
| tipe_lampiran | string | Attachment type (IMAGE/VIDEO/AUDIO/DOCUMENT/FILE) |
| url_lampiran | string | Attachment URL (max: 2048 characters) |
| ukuran_lampiran | integer | Attachment size in bytes |
| nama_file | string | File name (max: 255 characters) |
| mime_type | string | MIME type (max: 255 characters) |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Lampiran pesan berhasil diperbarui",
  "data": {...} // Updated message attachment resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/message-attachments/{id} - Delete message attachment

Delete a specific message attachment record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Message attachment ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Lampiran pesan berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Chat Product Reference Resource Endpoints

**Base Endpoint**: `/api/chat-product-references`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for chat product reference management.

#### GET /api/chat-product-references - List all chat product references

List all chat product references with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| pesan_id | integer | No | Filter by specific message |
| produk_id | integer | No | Filter by specific product |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data referensi produk chat berhasil diambil",
  "data": [...], // Array of chat product reference resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/chat-product-references - Create new chat product reference

Create a new chat product reference record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| pesan_id | integer | Yes | Message ID (must exist in chat_pesan_chat table) |
| produk_id | integer | Yes | Product ID (must exist in produk table) |
| posisi_mulai | integer | No | Start position in message |
| posisi_akhir | integer | No | End position in message |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Referensi produk chat berhasil dibuat",
  "data": {...} // Created chat product reference resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/chat-product-references/{id} - Get specific chat product reference

Retrieve a specific chat product reference's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat product reference ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data referensi produk chat berhasil diambil",
  "data": {...} // Chat product reference resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/chat-product-references/{id} - Update chat product reference

Update chat product reference information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat product reference ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| pesan_id | integer | Message ID (must exist in chat_pesan_chat table) |
| produk_id | integer | Product ID (must exist in produk table) |
| posisi_mulai | integer | Start position in message |
| posisi_akhir | integer | End position in message |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Referensi produk chat berhasil diperbarui",
  "data": {...} // Updated chat product reference resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/chat-product-references/{id} - Delete chat product reference

Delete a specific chat product reference record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat product reference ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Referensi produk chat berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Chat Order Reference Resource Endpoints

**Base Endpoint**: `/api/chat-order-references`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for chat order reference management.

#### GET /api/chat-order-references - List all chat order references

List all chat order references with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| pesan_id | integer | No | Filter by specific message |
| order_id | integer | No | Filter by specific order |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data referensi order chat berhasil diambil",
  "data": [...], // Array of chat order reference resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/chat-order-references - Create new chat order reference

Create a new chat order reference record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| pesan_id | integer | Yes | Message ID (must exist in chat_pesan_chat table) |
| order_id | integer | Yes | Order ID (must exist in orders table) |
| posisi_mulai | integer | No | Start position in message |
| posisi_akhir | integer | No | End position in message |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Referensi order chat berhasil dibuat",
  "data": {...} // Created chat order reference resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/chat-order-references/{id} - Get specific chat order reference

Retrieve a specific chat order reference's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat order reference ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data referensi order chat berhasil diambil",
  "data": {...} // Chat order reference resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/chat-order-references/{id} - Update chat order reference

Update chat order reference information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat order reference ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| pesan_id | integer | Message ID (must exist in chat_pesan_chat table) |
| order_id | integer | Order ID (must exist in orders table) |
| posisi_mulai | integer | Start position in message |
| posisi_akhir | integer | End position in message |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Referensi order chat berhasil diperbarui",
  "data": {...} // Updated chat order reference resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/chat-order-references/{id} - Delete chat order reference

Delete a specific chat order reference record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat order reference ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Referensi order chat berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

### Chat Report Resource Endpoints

**Base Endpoint**: `/api/chat-reports`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for chat report management.

#### GET /api/chat-reports - List all chat reports

List all chat reports with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| percakapan_id | integer | No | Filter by specific conversation |
| reporter_id | integer | No | Filter by specific reporter |
| tipe_laporan | string | No | Filter by report type |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data laporan chat berhasil diambil",
  "data": [...], // Array of chat report resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/chat-reports - Create new chat report

Create a new chat report record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| percakapan_id | integer | Yes | Conversation ID (must exist in chat_percakapan table) |
| reporter_id | integer | Yes | Reporter ID (must exist in users table) |
| alasan | string | Yes | Reason for report (max: 500 characters) |
| tipe_laporan | string | No | Report type (SPAM/HARASSMENT/INAPPROPRIATE_CONTENT/OTHER) |
| status_laporan | string | No | Report status (PENDING/RESOLVED/DISMISSED) |
| tanggapan_admin | string | No | Admin response |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Laporan chat berhasil dibuat",
  "data": {...} // Created chat report resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/chat-reports/{id} - Get specific chat report

Retrieve a specific chat report's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat report ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data laporan chat berhasil diambil",
  "data": {...} // Chat report resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/chat-reports/{id} - Update chat report

Update chat report information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat report ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| percakapan_id | integer | Conversation ID (must exist in chat_percakapan table) |
| reporter_id | integer | Reporter ID (must exist in users table) |
| alasan | string | Reason for report (max: 500 characters) |
| tipe_laporan | string | Report type (SPAM/HARASSMENT/INAPPROPRIATE_CONTENT/OTHER) |
| status_laporan | string | Report status (PENDING/RESOLVED/DISMISSED) |
| tanggapan_admin | string | Admin response |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Laporan chat berhasil diperbarui",
  "data": {...} // Updated chat report resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/chat-reports/{id} - Delete chat report

Delete a specific chat report record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | Chat report ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Laporan chat berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## System Settings API

### System Setting Resource Endpoints

**Base Endpoint**: `/api/system-settings`  
**Authentication**: Bearer token (Laravel Sanctum) for protected endpoints

Provides full CRUD operations for system setting management.

#### GET /api/system-settings - List all system settings

List all system settings with optional filtering, search, and pagination.

##### Query Parameters:

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| per_page | integer | No | Number of items per page (default: 15) |
| search | string | No | Search term to filter settings |
| kunci_pengaturan | string | No | Filter by setting key |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pengaturan sistem berhasil diambil",
  "data": [...], // Array of system setting resource objects
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

**Error (500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### POST /api/system-settings - Create new system setting

Create a new system setting record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| kunci_pengaturan | string | Yes | Setting key (max: 255 characters, must be unique) |
| nilai_pengaturan | string | Yes | Setting value |
| tipe_pengaturan | string | Yes | Setting type (STRING/NUMBER/BOOLEAN/JSON) |
| deskripsi_pengaturan | string | No | Setting description |
| grup_pengaturan | string | No | Setting group |
| dapat_diubah | boolean | No | Whether setting can be modified (default: true) |

##### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Pengaturan sistem berhasil dibuat",
  "data": {...} // Created system setting resource object
}
```

**Error (401/403/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors
}
```

---

#### GET /api/system-settings/{id} - Get specific system setting

Retrieve a specific system setting's detailed information.

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | System setting ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Data pengaturan sistem berhasil diambil",
  "data": {...} // System setting resource object with full details
}
```

**Error (404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

#### PUT/PATCH /api/system-settings/{id} - Update system setting

Update system setting information.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | System setting ID |

##### Request Body (optional fields):

| Field | Type | Description |
|-------|------|-------------|
| kunci_pengaturan | string | Setting key (max: 255 characters, must be unique) |
| nilai_pengaturan | string | Setting value |
| tipe_pengaturan | string | Setting type (STRING/NUMBER/BOOLEAN/JSON) |
| deskripsi_pengaturan | string | Setting description |
| grup_pengaturan | string | Setting group |
| dapat_diubah | boolean | Whether setting can be modified |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pengaturan sistem berhasil diperbarui",
  "data": {...} // Updated system setting resource object
}
```

**Error (401/403/404/422/500):**
```json
{
  "success": false,
  "message": "error message",
  "errors": {...} // Validation errors if applicable
}
```

---

#### DELETE /api/system-settings/{id} - Delete system setting

Delete a specific system setting record.

##### Headers:

| Header | Value |
|--------|-------|
| Authorization | Bearer {token} |

##### Path Parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| id | integer | System setting ID |

##### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Pengaturan sistem berhasil dihapus"
}
```

**Error (401/403/404/500):**
```json
{
  "success": false,
  "message": "error message"
}
```

---

## Response Format

All API responses follow a consistent format:

```json
{
  "success": true/false,
  "message": "descriptive message about the operation",
  "data": {...}, // optional data field
  "errors": {...}, // optional errors field for validation errors
  "pagination": {...} // optional pagination information
}
```

## Error Codes

| HTTP Code | Description |
|-----------|-------------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request - Invalid input data |
| 401 | Unauthorized - Invalid or missing token |
| 403 | Forbidden - Insufficient permissions |
| 404 | Not Found - Resource does not exist |
| 422 | Unprocessable Entity - Validation failed |
| 500 | Internal Server Error - Unexpected server error |
