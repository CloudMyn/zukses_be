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

Registers a new user account with the system.

#### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| username | string | Yes | Unique username (max: 255 characters) |
| email | string | Yes | Can be email address or phone number |
| kata_sandi | string | Yes | Password (min: 8 characters, must be confirmed) |
| nama_depan | string | Yes | First name (max: 255 characters) |
| nama_belakang | string | Yes | Last name (max: 255 characters) |

#### Response:

**Success (201):**
```json
{
  "success": true,
  "message": "Registrasi berhasil",
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

Authenticates a user and returns an access token.

#### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| contact | string | Yes | Email address or phone number |
| password | string | Yes | Password |
| device_id | string | Yes | Unique device identifier |
| device_name | string | Yes | Name of the device |
| operating_system | string | Yes | Operating system of the device |
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
    "device": {...} // Device resource object
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

Sends a one-time password (OTP) to the user for verification.

#### Request Body:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| contact | string | Yes | Email address or phone number |

#### Response:

**Success (200):**
```json
{
  "success": true,
  "message": "Kode OTP telah dikirim ke email/nomor telepon",
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