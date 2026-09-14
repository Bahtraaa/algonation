<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\FeaturedProductController;
use App\Http\Controllers\Admin\FlashSaleController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/featured', [HomeController::class, 'featured'])->name('featured');
Route::get('/flash-sale', [HomeController::class, 'flashSale'])->name('flash-sale');
Route::get('/about-us', [HomeController::class, 'about'])->name('about');
Route::get('/products/{product}', [HomeController::class, 'show'])->name('products.show');

Route::prefix('api/flash-sales')->group(function () {
    Route::get('/', [FlashSaleController::class, 'apiIndex']);
    Route::get('/{flashSale}', [FlashSaleController::class, 'apiShow']);
});

/*
|--------------------------------------------------------------------------
| Guest (Authentication) Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Alamat pengiriman milik user (Alamat Saya).
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::get('/addresses/create', [AddressController::class, 'create'])->name('addresses.create');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::get('/addresses/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::post('/addresses/{address}/default', [AddressController::class, 'setDefault'])->name('addresses.default');
});

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/orders', [CheckoutController::class, 'orders'])->name('orders');
    Route::get('/orders/{transaction}', [CheckoutController::class, 'orderDetail'])->name('orders.show');

    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/api/shipping/estimate', [CheckoutController::class, 'estimateShipping'])->name('shipping.estimate');
    Route::get('/receipt/{transaction}', [CheckoutController::class, 'receipt'])->name('checkout.receipt');
    Route::post('/orders/{transaction}/pay', [CheckoutController::class, 'pay'])->name('orders.pay');
    Route::post('/orders/{transaction}/check-status', [CheckoutController::class, 'checkStatus'])->name('orders.check-status');
    Route::post('/orders/{transaction}/finalize', [CheckoutController::class, 'finalize'])->name('orders.finalize');
    Route::delete('/orders/{transaction}/cancel', [CheckoutController::class, 'cancel'])->name('orders.cancel');

/*
|--------------------------------------------------------------------------
| Midtrans Notification (Webhook)
|--------------------------------------------------------------------------
*/

Route::post('/midtrans/notification', [CheckoutController::class, 'notification'])->name('midtrans.notification');

/*
|--------------------------------------------------------------------------
| Cart (Session based, can be guest or auth)
|--------------------------------------------------------------------------
*/

Route::get('/cart/state', [CartController::class, 'state'])->name('cart.state');
Route::post('/cart/add', [CartController::class, 'add'])
    ->middleware('auth')
    ->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

/*
|--------------------------------------------------------------------------
| Admin Routes (RBAC)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Product management (data utama produk SAJA — tanpa form variant).
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::post('products/{product}/stock', [AdminProductController::class, 'addStock'])->name('products.stock');

    // Produk Variant (menu standalone — controller, route, view terpisah).
    Route::resource('product-variants', ProductVariantController::class)->except(['show']);

    // Legacy nested variant routes (deprecated, dipertahankan agar tidak merusak
    // integrasi lama — UI admin tidak lagi menggunakannya).
    Route::post('products/{product}/variants', [AdminProductController::class, 'storeVariant'])->name('products.variants.store');
    Route::put('variants/{variant}', [AdminProductController::class, 'updateVariant'])->name('products.variants.update');
    Route::post('variants/{variant}/stock', [AdminProductController::class, 'addVariantStock'])->name('products.variants.stock');
    Route::delete('variants/{variant}', [AdminProductController::class, 'destroyVariant'])->name('products.variants.destroy');

    // Sales report
    Route::get('sales', [SalesController::class, 'index'])->name('sales.index');
    Route::get('sales/export/csv', [SalesController::class, 'exportCsv'])->name('sales.export.csv');
    Route::get('sales/print', [SalesController::class, 'print'])->name('sales.print');

    // User management
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::patch('users/{user}/role', [UserController::class, 'updateRole'])->name('users.role');
    Route::patch('users/{user}/status', [UserController::class, 'toggleStatus'])->name('users.status');

    // Order / Shipping management
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{transaction}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('orders/{transaction}/shipping', [OrderController::class, 'updateShipping'])->name('orders.shipping.update');

    // Flash sale management
    Route::get('flash-sales', [FlashSaleController::class, 'index'])->name('flash-sales.index');
    Route::post('flash-sales', [FlashSaleController::class, 'store'])->name('flash-sales.store');
    Route::put('flash-sales/{flashSale}', [FlashSaleController::class, 'update'])->name('flash-sales.update');
    Route::delete('flash-sales/{flashSale}', [FlashSaleController::class, 'destroy'])->name('flash-sales.destroy');
    Route::patch('flash-sales/{flashSale}/status', [FlashSaleController::class, 'toggle'])->name('flash-sales.status');

    // Featured products (Produk Unggulan) — references existing products only.
    Route::get('featured-products', [FeaturedProductController::class, 'index'])->name('featured-products.index');
    Route::post('featured-products', [FeaturedProductController::class, 'store'])->name('featured-products.store');
    Route::put('featured-products/{featuredProduct}', [FeaturedProductController::class, 'update'])->name('featured-products.update');
    Route::delete('featured-products/{featuredProduct}', [FeaturedProductController::class, 'destroy'])->name('featured-products.destroy');

    // Shipping configuration (origin, zones, international regions, couriers).
    Route::get('shipping', [ShippingController::class, 'index'])->name('shipping.index');
    Route::put('shipping/settings', [ShippingController::class, 'updateSettings'])->name('shipping.settings.update');
    Route::post('shipping/zones', [ShippingController::class, 'storeZone'])->name('shipping.zones.store');
    Route::put('shipping/zones/{zone}', [ShippingController::class, 'updateZone'])->name('shipping.zones.update');
    Route::delete('shipping/zones/{zone}', [ShippingController::class, 'destroyZone'])->name('shipping.zones.destroy');
    Route::post('shipping/regions', [ShippingController::class, 'storeRegion'])->name('shipping.regions.store');
    Route::put('shipping/regions/{region}', [ShippingController::class, 'updateRegion'])->name('shipping.regions.update');
    Route::delete('shipping/regions/{region}', [ShippingController::class, 'destroyRegion'])->name('shipping.regions.destroy');
    Route::post('shipping/countries', [ShippingController::class, 'storeCountry'])->name('shipping.countries.store');
    Route::put('shipping/countries/{country}', [ShippingController::class, 'updateCountry'])->name('shipping.countries.update');
    Route::delete('shipping/countries/{country}', [ShippingController::class, 'destroyCountry'])->name('shipping.countries.destroy');
    Route::post('shipping/couriers', [ShippingController::class, 'storeCourier'])->name('shipping.couriers.store');
    Route::delete('shipping/couriers/{courier}', [ShippingController::class, 'destroyCourier'])->name('shipping.couriers.destroy');
});
