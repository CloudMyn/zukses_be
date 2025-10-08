# API Documentation - Authentication

## Base URL
`/api/auth`

## Endpoints

### 1. Register User
- **URL**: `POST /api/auth/register`
- **Description**: Register new user
- **Headers**:
  - `Content-Type: application/json`
  - `Accept: application/json`
- **Body**:
  ```json
  {
    "username": "johndoe",
    "email": "johndoe@example.com",  // or phone number like "+6281234567890"
    "kata_sandi": "password123",
    "kata_sandi_confirmation": "password123",
    "nama_depan": "John",
    "nama_belakang": "Doe"
  }
  ```
- **Response Success**:
  ```json
  {
    "success": true,
    "message": "Registrasi berhasil",
    "data": {
      "user": {
        "id": 1,
        "username": "johndoe",
        "email": "johndoe@example.com",
        "nomor_telepon": "+6281234567890",
        // ... other user data
      },
      "token": "1|abc123def456..."
    }
  }
  ```

### 2. Login User
- **URL**: `POST /api/auth/login`
- **Description**: Login user with password
- **Headers**:
  - `Content-Type: application/json`
  - `Accept: application/json`
- **Body**:
  ```json
  {
    "contact": "johndoe@example.com",  // or phone number like "+6281234567890"
    "password": "password123",
    "device_id": "device-uuid-123",
    "device_name": "Samsung Galaxy S21",
    "operating_system": "Android",
    "app_version": "1.0.0",
    "push_token": "push-token-here"
  }
  ```
- **Response Success**:
  ```json
  {
    "success": true,
    "message": "Login berhasil",
    "data": {
      "user": {
        "id": 1,
        "username": "johndoe",
        "email": "johndoe@example.com",
        // ... other user data
      },
      "token": "1|abc123def456...",
      "device": {
        "id": 1,
        "device_id": "device-uuid-123",
        "device_name": "Samsung Galaxy S21",
        // ... other device data
      }
    }
  }
  ```
- **Response Error**:
  ```json
  {
    "success": false,
    "message": "Akun tidak ditemukan"
  }
  ```  
  ```json
  {
    "success": false,
    "message": "Password salah"
  }
  ```

### 3. Send OTP Code
- **URL**: `POST /api/auth/send-otp`
- **Description**: Send OTP code to user's email or phone
- **Headers**:
  - `Content-Type: application/json`
  - `Accept: application/json`
- **Body**:
  ```json
  {
    "contact": "johndoe@example.com"  // or phone number like "+6281234567890"
  }
  ```
- **Response Success**:
  ```json
  {
    "success": true,
    "message": "Kode OTP telah dikirim",
    "data": {
      "verification_id": 1,
      "expires_at": "2023-12-01T12:00:00.000000Z",
      "verification_type": "EMAIL"  // or "TELEPON"
    }
  }
  ```

### 4. Get User Profile
- **URL**: `GET /api/auth/me`
- **Description**: Get authenticated user's profile
- **Headers**:
  - `Authorization: Bearer {token}`
  - `Accept: application/json`
- **Response Success**:
  ```json
  {
    "success": true,
    "data": {
      "id": 1,
      "username": "johndoe",
      "email": "johndoe@example.com",
      // ... other user data
    }
  }
  ```

### 5. Get User Profile
- **URL**: `GET /api/auth/profile`
- **Description**: Get authenticated user's profile
- **Headers**:
  - `Authorization: Bearer {token}`
  - `Accept: application/json`
- **Response Success**:
  ```json
  {
    "success": true,
    "data": {
      "id": 1,
      "username": "johndoe",
      "email": "johndoe@example.com",
      // ... other user data
    }
  }
  ```

### 6. Update User Profile
- **URL**: `PUT /api/auth/profile`
- **Description**: Update authenticated user's profile
- **Headers**:
  - `Authorization: Bearer {token}`
  - `Content-Type: application/json`
  - `Accept: application/json`
- **Body**:
  ```json
  {
    "username": "johndoe_new",
    "nama_depan": "John",
    "nama_belakang": "Doe Updated",
    "jenis_kelamin": "LAKI_LAKI",
    "tanggal_lahir": "1990-01-01",
    "bio": "This is my bio",
    "kata_sandi": "newpassword123",
    "kata_sandi_confirmation": "newpassword123"
  }
  ```
- **Response Success**:
  ```json
  {
    "success": true,
    "message": "Profil berhasil diperbarui",
    "data": {
      "id": 1,
      "username": "johndoe_new",
      "email": "johndoe@example.com",
      // ... other user data
    }
  }
  ```

### 7. Logout
- **URL**: `POST /api/auth/logout`
- **Description**: Logout authenticated user
- **Headers**:
  - `Authorization: Bearer {token}`
  - `Accept: application/json`
- **Response Success**:
  ```json
  {
    "success": true,
    "message": "Logout berhasil"
  }
  ```

### 8. Delete Account
- **URL**: `DELETE /api/auth/delete-account`
- **Description**: Delete authenticated user's account
- **Headers**:
  - `Authorization: Bearer {token}`
  - `Content-Type: application/json`
  - `Accept: application/json`
- **Body**:
  ```json
  {
    "password": "current_password"
  }
  ```
- **Response Success**:
  ```json
  {
    "success": true,
    "message": "Akun berhasil dihapus"
  }
  ```

### 9. Forgot Password
- **URL**: `POST /api/auth/forgot-password`
- **Description**: Request OTP for password reset
- **Headers**:
  - `Content-Type: application/json`
  - `Accept: application/json`
- **Body**:
  ```json
  {
    "contact": "johndoe@example.com"  // or phone number like "+6281234567890"
  }
  ```
- **Response Success**:
  ```json
  {
    "success": true,
    "message": "Kode OTP telah dikirim untuk reset password",
    "data": {
      "verification_id": 1,
      "expires_at": "2023-12-01T12:00:00.000000Z",
      "verification_type": "EMAIL"  // or "TELEPON"
    }
  }
  ```

### 9. Verify OTP
- **URL**: `POST /api/auth/verify-otp`
- **Description**: Verify OTP for password reset
- **Headers**:
  - `Content-Type: application/json`
  - `Accept: application/json`
- **Body**:
  ```json
  {
    "contact": "johndoe@example.com",  // or phone number like "+6281234567890"
    "otp_code": "123456"
  }
  ```
- **Response Success**:
  ```json
  {
    "success": true,
    "message": "OTP terverifikasi, silakan atur password baru",
    "data": {
      "user_id": 1
    }
  }
  ```

### 10. Reset Password
- **URL**: `POST /api/auth/reset-password`
- **Description**: Reset password after OTP verification
- **Headers**:
  - `Content-Type: application/json`
  - `Accept: application/json`
- **Body**:
  ```json
  {
    "user_id": 1,
    "new_password": "newpassword123",
    "new_password_confirmation": "newpassword123"
  }
  ```
- **Response Success**:
  ```json
  {
    "success": true,
    "message": "Password berhasil direset"
  }
  ```

### 11. Google Login - Redirect
- **URL**: `GET /api/auth/google`
- **Description**: Redirect to Google for authentication
- **Headers**:
  - `Accept: application/json`
- **Response**: Redirects to Google OAuth page

### 12. Google Login - Callback
- **URL**: `GET /api/auth/google/callback`
- **Description**: Handle Google OAuth callback
- **Headers**:
  - `Accept: application/json`
- **Query Parameters**:
  ```json
  {
    "code": "oauth_code_from_google",
    "device_id": "device-uuid-123",
    "device_name": "Samsung Galaxy S21",
    "operating_system": "Android",
    "app_version": "1.0.0",
    "push_token": "push-token-here"
  }
  ```
- **Response Success**:
  ```json
  {
    "success": true,
    "message": "Login dengan Google berhasil",
    "data": {
      "user": {
        "id": 1,
        "username": "johndoe",
        "email": "johndoe@gmail.com",
        // ... other user data
      },
      "token": "1|abc123def456...",
      "device": {
        "id": 1,
        "device_id": "device-uuid-123",
        "device_name": "Samsung Galaxy S21",
        // ... other device data
      }
    }
  }
  ```
- **Response Error**:
  ```json
  {
    "success": false,
    "message": "Terjadi kesalahan saat login dengan Google: error_message"
  }
  ```