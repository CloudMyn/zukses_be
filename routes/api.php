<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rute untuk autentikasi
Route::prefix('auth')->group(function () {
    // Registrasi pengguna
    Route::post('/register', [AuthController::class, 'register']);
    
    // Login pengguna
    Route::post('/login', [AuthController::class, 'login']);
    
    // Kirim OTP
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
    
    // Lupa password
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    
    // Verifikasi OTP
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    
    // Reset password
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    
    // Google Login
    Route::get('/google', [AuthController::class, 'redirectToGoogle']);
    Route::get('/google/callback', [AuthController::class, 'handleGoogleCallback']);
    
    // Logout (memerlukan autentikasi)
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
    
    // Dapatkan data pengguna saat ini (memerlukan autentikasi)
    Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'me']);
    
    // Update profil (memerlukan autentikasi)
    Route::middleware('auth:sanctum')->put('/profile', [UserController::class, 'updateProfile']);
    
    // Hapus akun (memerlukan autentikasi)
    Route::middleware('auth:sanctum')->delete('/delete-account', [UserController::class, 'deleteAccount']);
    
    // Dapatkan profil pengguna saat ini (memerlukan autentikasi)
    Route::middleware('auth:sanctum')->get('/profile', [UserController::class, 'showProfile']);
});

// Rute untuk pengguna yang sudah login
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});