<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PromosiController;

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
    Route::middleware('jwt.verify')->post('/logout', [AuthController::class, 'logout']);

    // Dapatkan data pengguna saat ini (memerlukan autentikasi)
    Route::middleware('jwt.verify')->get('/me', [AuthController::class, 'me']);

    // Update profil (memerlukan autentikasi)
    Route::middleware('jwt.verify')->put('/profile', [UserController::class, 'updateProfile']);

    // Hapus akun (memerlukan autentikasi)
    Route::middleware('jwt.verify')->delete('/delete-account', [UserController::class, 'deleteAccount']);

    // Dapatkan profil pengguna saat ini (memerlukan autentikasi)
    Route::middleware('jwt.verify')->get('/profile', [UserController::class, 'showProfile']);
});

// Rute untuk pengguna yang sudah login
Route::middleware('jwt.verify')->get('/user', function (Request $request) {
    return $request->user();
});

// Public Seller Routes
Route::get('/sellers', [\App\Http\Controllers\Api\SellerController::class, 'index']);
Route::get('/sellers/{seller}', [\App\Http\Controllers\Api\SellerController::class, 'show']);
Route::get('/sellers/{seller}/products', [\App\Http\Controllers\Api\SellerController::class, 'products']);
Route::get('/sellers/{seller}/reviews', [\App\Http\Controllers\Api\SellerController::class, 'reviews']);
Route::get('/sellers/{seller}/ratings', [\App\Http\Controllers\Api\SellerController::class, 'ratings']);

// Phase 1: Core User Management APIs
Route::middleware('jwt.verify')->group(function () {
    Route::apiResource('users', UserController::class);

    // Protected Seller Routes (store, update, destroy)
    Route::post('/sellers', [\App\Http\Controllers\Api\SellerController::class, 'store']);
    Route::put('/sellers/{seller}', [\App\Http\Controllers\Api\SellerController::class, 'update']);
    Route::delete('/sellers/{seller}', [\App\Http\Controllers\Api\SellerController::class, 'destroy']);

    Route::apiResource('devices', \App\Http\Controllers\Api\DeviceController::class);
    Route::post('/devices/{device}/trust', [\App\Http\Controllers\Api\DeviceController::class, 'trust']);

    Route::apiResource('verifications', \App\Http\Controllers\Api\VerificationController::class);

    Route::apiResource('sessions', \App\Http\Controllers\Api\SessionController::class);
});

// Phase 2: Address & Location APIs
Route::middleware('jwt.verify')->group(function () {
    Route::apiResource('addresses', \App\Http\Controllers\Api\AddressController::class);
    
    Route::apiResource('provinces', \App\Http\Controllers\Api\ProvinceController::class);
    
    Route::apiResource('cities', \App\Http\Controllers\Api\CityController::class);
    
    Route::apiResource('districts', \App\Http\Controllers\Api\DistrictController::class);
    
    Route::apiResource('postal-codes', \App\Http\Controllers\Api\PostalCodeController::class);
});

// Phase 3: Product Management APIs
Route::middleware('jwt.verify')->group(function () {
    Route::apiResource('categories', \App\Http\Controllers\Api\CategoryProductController::class);
    
    Route::apiResource('products', \App\Http\Controllers\Api\ProductController::class);
    
    Route::apiResource('product-variants', \App\Http\Controllers\Api\ProductVariantController::class);
    
    Route::apiResource('product-variant-values', \App\Http\Controllers\Api\ProductVariantValueController::class);
    
    Route::apiResource('product-variant-prices', \App\Http\Controllers\Api\ProductVariantPriceController::class);
    
    Route::apiResource('product-images', \App\Http\Controllers\Api\ProductImageController::class);
    
    Route::apiResource('product-shipping-info', \App\Http\Controllers\Api\ProductShippingInfoController::class);
    
    Route::apiResource('inventory-logs', \App\Http\Controllers\Api\InventoryLogController::class);
    
    Route::apiResource('variant-price-compositions', \App\Http\Controllers\Api\VariantPriceCompositionController::class);
    
    Route::apiResource('variant-shipping-info', \App\Http\Controllers\Api\VariantShippingInfoController::class);
});

// Phase 4: Shopping & Order APIs
Route::middleware('jwt.verify')->group(function () {
    Route::apiResource('carts', \App\Http\Controllers\Api\CartController::class);
    
    Route::apiResource('cart-items', \App\Http\Controllers\Api\CartItemController::class);
    
    Route::apiResource('orders', \App\Http\Controllers\Api\OrderController::class);
    
    Route::apiResource('order-items', \App\Http\Controllers\Api\OrderItemController::class);
    
    Route::apiResource('order-status-history', \App\Http\Controllers\Api\OrderStatusHistoryController::class);
});

// Phase 5: Payment & Shipping APIs
Route::middleware('jwt.verify')->group(function () {
    Route::apiResource('shipping-methods', \App\Http\Controllers\Api\ShippingMethodController::class);
    
    Route::apiResource('shipping-rates', \App\Http\Controllers\Api\ShippingRateController::class);
    
    Route::apiResource('order-shipping', \App\Http\Controllers\Api\OrderShippingController::class);
    
    Route::apiResource('payment-methods', \App\Http\Controllers\Api\PaymentMethodController::class);
    
    Route::apiResource('payment-transactions', \App\Http\Controllers\Api\PaymentTransactionController::class);
    
    Route::apiResource('payment-logs', \App\Http\Controllers\Api\PaymentLogController::class);
});

// Phase 6: Review & Feedback APIs
Route::middleware('jwt.verify')->group(function () {
    Route::apiResource('product-reviews', \App\Http\Controllers\Api\ProductReviewController::class);
    
    Route::apiResource('review-media', \App\Http\Controllers\Api\ReviewMediaController::class);
    
    Route::apiResource('review-votes', \App\Http\Controllers\Api\ReviewVoteController::class);
});

// Phase 7: Notification & Activity APIs
Route::middleware('jwt.verify')->group(function () {
    Route::apiResource('notifications', \App\Http\Controllers\Api\UserNotificationController::class);
    
    Route::apiResource('activities', \App\Http\Controllers\Api\UserActivityController::class);
    
    Route::apiResource('search-history', \App\Http\Controllers\Api\SearchHistoryController::class);
});

// Phase 8: Admin & Reporting APIs
Route::middleware('jwt.verify')->group(function () {
    Route::apiResource('admin-users', \App\Http\Controllers\Api\AdminController::class);
    
    Route::apiResource('seller-reports', \App\Http\Controllers\Api\SellerReportController::class);
    
    Route::apiResource('sales-reports', \App\Http\Controllers\Api\SalesReportController::class);
    
    // Promotion management routes
    Route::apiResource('promotions', PromosiController::class);
    Route::post('promotions/validate', [PromosiController::class, 'validatePromo']);
});

// Phase 9: Chat System APIs
Route::middleware('jwt.verify')->group(function () {
    Route::apiResource('chat-conversations', \App\Http\Controllers\Api\ChatConversationController::class);
    
    Route::apiResource('chat-participants', \App\Http\Controllers\Api\ChatParticipantController::class);
    
    Route::apiResource('chat-messages', \App\Http\Controllers\Api\ChatMessageController::class);
    
    Route::apiResource('message-statuses', \App\Http\Controllers\Api\MessageStatusController::class);
    
    Route::apiResource('message-reactions', \App\Http\Controllers\Api\MessageReactionController::class);
    
    Route::apiResource('message-edits', \App\Http\Controllers\Api\MessageEditController::class);
    
    Route::apiResource('message-attachments', \App\Http\Controllers\Api\MessageAttachmentController::class);
    
    Route::apiResource('chat-product-references', \App\Http\Controllers\Api\ChatProductReferenceController::class);
    
    Route::apiResource('chat-order-references', \App\Http\Controllers\Api\ChatOrderReferenceController::class);
    
    Route::apiResource('chat-reports', \App\Http\Controllers\Api\ChatReportController::class);
});

// Phase 10: System Settings APIs
Route::middleware('jwt.verify')->group(function () {
    Route::apiResource('system-settings', \App\Http\Controllers\Api\SystemSettingController::class);
});