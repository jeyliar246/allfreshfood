<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CuisineController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Public data routes
Route::get('/vendors', [VendorController::class, 'index']);
Route::get('/vendors/{id}', [VendorController::class, 'show']);
Route::get('/vendors/{id}/products', [VendorController::class, 'products']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/cuisines', [CuisineController::class, 'index']);
Route::get('/payment-methods', [PaymentController::class, 'getPaymentMethods']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Order routes
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus']);
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel']);

    // Payment routes
    Route::post('/payments/initiate', [PaymentController::class, 'initiatePayment']);

    // Product routes (vendor only)
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    // Vendor profile routes
    Route::put('/vendors/{id}', [VendorController::class, 'update']);

    // Commission routes
    Route::get('/commissions', [CommissionController::class, 'getVendorCommissions']);

    // Payout routes
    Route::post('/payouts/request', [PayoutController::class, 'requestPayout']);
    Route::get('/payouts', [PayoutController::class, 'getPayouts']);

    // Review routes
    Route::post('/reviews/vendor', [ReviewController::class, 'submitVendorReview']);
    Route::post('/reviews/product', [ReviewController::class, 'submitProductReview']);

    // Favorite routes
    Route::post('/favorites/vendors/{vendorId}', [FavoriteController::class, 'toggleVendorFavorite']);
    Route::post('/favorites/products/{productId}', [FavoriteController::class, 'toggleProductFavorite']);
    Route::get('/favorites/vendors', [FavoriteController::class, 'getFavoriteVendors']);
    Route::get('/favorites/products', [FavoriteController::class, 'getFavoriteProducts']);

    // Coupon routes
    Route::get('/coupons', [CouponController::class, 'index']);
    Route::post('/coupons/apply', [CouponController::class, 'applyCoupon']);

    // Analytics routes
    Route::get('/analytics/vendor', [AnalyticsController::class, 'getVendorAnalytics']);

    // Admin routes
    Route::middleware('admin')->group(function () {
        Route::post('/vendors/{id}/verify', [VendorController::class, 'verify']);
        Route::post('/vendors/{id}/feature', [VendorController::class, 'feature']);
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('cuisines', CuisineController::class);
        Route::apiResource('payment-methods', PaymentMethodController::class);
        Route::get('/analytics/platform', [AnalyticsController::class, 'getPlatformAnalytics']);
        Route::get('/payouts/pending', [PayoutController::class, 'getPendingPayouts']);
        Route::post('/payouts/{id}/process', [PayoutController::class, 'processPayout']);
    });
});

// Favorite routes
Route::post('/favorites/vendors/{vendorId}', [FavoriteController::class, 'toggleVendorFavorite']);
Route::post('/favorites/products/{productId}', [FavoriteController::class, 'toggleProductFavorite']);
Route::get('/favorites/vendors', [FavoriteController::class, 'getFavoriteVendors']);
Route::get('/favorites/products', [FavoriteController::class, 'getFavoriteProducts']);
Route::get('/favorites/vendors/{vendorId}/check', [FavoriteController::class, 'checkVendorFavorite']);
Route::get('/favorites/products/{productId}/check', [FavoriteController::class, 'checkProductFavorite']);

// Analytics tracking routes
Route::post('/analytics/products/{productId}/view', [AnalyticsController::class, 'recordProductView']);
Route::post('/analytics/vendors/{vendorId}/view', [AnalyticsController::class, 'recordVendorView']);

// Admin coupon management
Route::post('/admin/coupons', [CouponController::class, 'store'])->middleware('admin');

// Payment method management (admin)
Route::apiResource('payment-methods', PaymentMethodController::class)->middleware('admin');
Route::post('/payment-methods/{id}/toggle-status', [PaymentMethodController::class, 'toggleStatus'])->middleware('admin');

// Payment callback (public)
Route::post('/payment-callback', [PaymentController::class, 'paymentCallback']);
