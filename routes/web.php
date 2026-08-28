<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
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

});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Customer (Authenticated) Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/orders', [CheckoutController::class, 'orders'])->name('orders');

    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/receipt/{transaction}', [CheckoutController::class, 'receipt'])->name('checkout.receipt');
    Route::delete('/orders/{transaction}/cancel', [CheckoutController::class, 'cancel'])->name('orders.cancel');
});

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
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard.index');

    // Product management
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::post('products/{product}/stock', [AdminProductController::class, 'addStock'])->name('products.stock');
    Route::post('products/{product}/variants', [AdminProductController::class, 'storeVariant'])->name('products.variants.store');
    Route::put('variants/{variant}', [AdminProductController::class, 'updateVariant'])->name('products.variants.update');
    Route::post('variants/{variant}/stock', [AdminProductController::class, 'addVariantStock'])->name('products.variants.stock');
    Route::delete('variants/{variant}', [AdminProductController::class, 'destroyVariant'])->name('products.variants.destroy');

    // Stock report
    Route::get('stock', [StockController::class, 'index'])->name('stock.index');

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
});

