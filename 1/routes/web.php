<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CuisineController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\VendorOrderController;
use App\Http\Controllers\VendorFinanceController;
use App\Http\Controllers\DeliveryAmountController;
use App\Http\Controllers\CookieConsentController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Authentication & Registration
Route::get('/login', [HomeController::class, 'login'])->name('home.login');
Route::get('/register', [HomeController::class, 'register'])->name('home.register');
Route::get('/vending', [HomeController::class, 'vending'])->name('home.vending');
Route::post('/register-vendor', [VendorController::class, 'registerVendor'])->name('registerVendor');

// Verification  
Route::get('/verify', [HomeController::class, 'verify'])->name('verify');
Route::post('/verify-code', [HomeController::class, 'verifyCode'])->name('verifyCode');
Route::get('/resend-verify-code', [HomeController::class, 'resendVerifyCode'])->name('resendVerifyCode');

// Password Reset
Route::get('/forgot-password', [HomeController::class, 'forgotPassword'])->name('home.forgot-password');
Route::get('/reset-password', [HomeController::class, 'resetPassword'])->name('home.reset-password');

// Home & Browsing
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/browse', [HomeController::class, 'browse'])->name('home.browse');
Route::get('/products/{product}', [HomeController::class, 'product'])->name('home.product');
Route::get('/cuisines', [HomeController::class, 'cuisine'])->name('home.cuisines');
Route::get('/front/vendors', [HomeController::class, 'vendors'])->name('home.vendors');
Route::get('/vendor/{id}', [HomeController::class, 'vendor'])->whereNumber('id')->name('vendor.shop');

// AI Features
Route::get('/ai-shopping-assistant', [AiController::class, 'ai'])->name('home.ai');
Route::post('/ai/chat', [AiController::class, 'chat'])->name('ai.chat');

// Static Informational Pages
Route::get('/about', fn() => view('home.about'))->name('home.about');
Route::get('/contact', fn() => view('home.contact'))->name('home.contact');
Route::get('/help', fn() => view('home.help'))->name('home.help');
Route::get('/privacy', fn() => view('home.privacy'))->name('home.privacy');
Route::get('/become-vendor', fn() => view('home.become-vendor'))->name('home.become-vendor');
Route::get('/how-it-works', fn() => view('home.how-it-works'))->name('home.how');
Route::get('/careers', fn() => view('home.careers'))->name('home.careers');
Route::get('/delivery', fn() => view('home.delivery-info'))->name('home.delivery');
Route::get('/returns', fn() => view('home.returns'))->name('home.returns');
Route::get('/terms', fn() => view('home.terms'))->name('home.terms');
Route::get('/cookies', fn() => view('home.cookies'))->name('home.cookies');
Route::get('/accessibility', fn() => view('home.accessibility'))->name('home.accessibility');

// Cookie Consent (GDPR)
Route::prefix('consent')->name('consent.')->group(function () {
    Route::get('/status', [CookieConsentController::class, 'status'])->name('status');
    Route::post('/accept-all', [CookieConsentController::class, 'acceptAll'])->name('accept-all');
    Route::post('/reject-all', [CookieConsentController::class, 'rejectAll'])->name('reject-all');
    Route::post('/save', [CookieConsentController::class, 'save'])->name('save');
});

// Cart Routes
Route::get('/cart', [CartController::class, 'cart'])->name('home.cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Checkout (Public to allow guest checkout)
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::post('/process-checkout', [CartController::class, 'processCheckout'])->name('processCheckout');
Route::get('/order-confirmation', [CartController::class, 'orderConfirmation'])->name('order-confirmation');

// Payments (Stripe/Cashier Integration) - success/cancel must be public to support guest
Route::get('/payments/success', [PaymentController::class, 'success'])->name('payments.success');
Route::get('/payments/cancel', [PaymentController::class, 'cancel'])->name('payments.cancel');

/*
|--------------------------------------------------------------------------
| Protected Routes (Auth + Verified)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Routes
    Route::get('/admin/users', [DashboardController::class, 'users'])->name('admin.users');
    Route::get('/admin/orders', [DashboardController::class, 'orders'])->name('admin.orders');
    Route::get('/admin/delivery', [DashboardController::class, 'delivery'])->name('admin.delivery');

    // Vendor Management
    Route::resource('vendors', VendorController::class);
    Route::get('create/vendor', [VendorController::class, 'create'])->name('vendors.create');
    Route::patch('/vendors/{vendor}/approve', [VendorController::class, 'approve'])->name('vendors.approve');
    Route::patch('/vendors/{vendor}/suspend', [VendorController::class, 'suspend'])->name('vendors.suspend');
    Route::get('/vendors/{vendor}', [VendorController::class, 'show'])->name('vendors.show');

    // Categories Management
    Route::prefix('dashboard/categories')->name('dashboard.categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'categories'])->name('index');
        Route::get('/create', [CategoryController::class, 'createCategory'])->name('create');
        Route::post('/', [CategoryController::class, 'storeCategory'])->name('store');
        Route::get('/{category}/edit', [CategoryController::class, 'editCategory'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'updateCategory'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroyCategory'])->name('destroy');
    });

    // Products Management
    Route::prefix('dashboard/products')->name('dashboard.products.')->group(function () {
        Route::get('/', [ProductController::class, 'products'])->name('index');
        Route::get('/create', [ProductController::class, 'createProduct'])->name('create');
        Route::post('/', [ProductController::class, 'storeProduct'])->name('store');
        Route::get('/{product}', [ProductController::class, 'showProduct'])->name('show');
        Route::get('/{product}/edit', [ProductController::class, 'editProduct'])->name('edit');
        Route::put('/{product}', [ProductController::class, 'updateProduct'])->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroyProduct'])->name('destroy');
    });

    // Cuisines Management
    Route::prefix('dashboard/cuisines')->name('dashboard.cuisines.')->group(function () {
        Route::get('/', [CuisineController::class, 'index'])->name('index');
        Route::get('/create', [CuisineController::class, 'createCuisine'])->name('create');
        Route::post('/', [CuisineController::class, 'storeCuisine'])->name('store');
        Route::get('/{cuisine}', [CuisineController::class, 'showCuisine'])->name('show');
        Route::get('/{cuisine}/edit', [CuisineController::class, 'editCuisine'])->name('edit');
        Route::put('/{cuisine}', [CuisineController::class, 'updateCuisine'])->name('update');
        Route::delete('/{cuisine}', [CuisineController::class, 'destroyCuisine'])->name('destroy');
    });

    // Order Status Update
    Route::post('/orders/{order}/status', [DashboardController::class, 'updateOrderStatus'])
        ->name('orders.updateStatus');

    // Vendor Routes: Orders and Finance
    Route::prefix('vendor')->name('vendor.')->group(function () {
        Route::get('/orders', [VendorOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [VendorOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::get('/finance', [VendorFinanceController::class, 'index'])->name('finance.index');
        Route::post('/withdrawals', [VendorFinanceController::class, 'storeWithdrawal'])->name('withdrawals.store');
        Route::post('/bank-details', [VendorFinanceController::class, 'storeBankDetails'])->name('bank-details.store');
    });

    // Admin: Payouts Management
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/payouts', [PayoutController::class, 'adminIndex'])->name('payouts.index');
        Route::post('/payouts/{id}/approve', [PayoutController::class, 'approve'])->name('payouts.approve');
        Route::post('/payouts/{id}/pay', [PayoutController::class, 'processPayout'])->name('payouts.pay');
    });

    // Distributors
    Route::resource('distributors', DistributorController::class);

    // Users Management
    Route::post('/users/{id}/destroy', [DashboardController::class, 'destroy'])
        ->name('users.destroy');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Orders (requires auth)
    Route::get('/orders', [CartController::class, 'orders'])->name('home.orders');

    // Payments (Stripe/Cashier Integration)
    Route::post('/payments/create-intent', [PaymentController::class, 'createPaymentIntent'])->name('payments.create-intent');
    Route::post('/payments/create-checkout-session', [PaymentController::class, 'createCheckoutSession'])->name('payments.create-checkout-session');

    // Delivery management
    Route::post('/delivery/{delivery}/assign', [DeliveryController::class, 'assign'])->name('delivery.assign');
    Route::post('/delivery/{delivery}/pickup', [DeliveryController::class, 'pickup'])->name('delivery.pickup');
    Route::post('/delivery/{delivery}/deliver', [DeliveryController::class, 'deliver'])->name('delivery.deliver');
    Route::get('/delivery/amounts', [DeliveryAmountController::class, 'index'])->name('delivery.amounts');
    Route::post('/delivery/amounts', [DeliveryAmountController::class, 'store'])->name('delivery.amounts.store');
    Route::get('/delivery/amounts/{deliveryAmount}/edit', [DeliveryAmountController::class, 'edit'])->name('delivery.amounts.edit');
    Route::put('/delivery/amounts/{deliveryAmount}', [DeliveryAmountController::class, 'update'])->name('delivery.amounts.update');
});

require __DIR__.'/auth.php';