<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Public view
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');

Auth::routes();

/*
|--------------------------------------------------------------------------
| User role
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'userDashboard'])->name('dashboard');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout & Orders
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'placeOrder'])->name('checkout.place');
    Route::get('/orders', [OrderController::class, 'userOrders'])->name('orders.index');

    // Reviews
    Route::post('/reviews/{product}', [ReviewController::class, 'store'])->name('reviews.store');

    // Addresses
    Route::resource('addresses', AddressController::class);
});

/*
|--------------------------------------------------------------------------
| Supplier role
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:supplier'])->prefix('supplier')->name('supplier.')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'supplierDashboard'])->name('dashboard');

    // Supplier's products
    Route::get('/products/data', [ProductController::class, 'supplierData'])->name('products.data');
    Route::resource('products', ProductController::class)->except(['show']);

    // View orders that include their products
    Route::get('/orders', [OrderController::class, 'supplierOrders'])->name('orders.index');
});

/*
|--------------------------------------------------------------------------
| Admin role
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin|superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'adminDashboard'])->name('dashboard');

    // Managing Products
    Route::resource('products', ProductController::class)->except(['show']);

    //Orders
    Route::get('/orders', [OrderController::class, 'adminOrders'])->name('orders.index');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Categories
    Route::get('/categories/data', [CategoryController::class, 'getData'])->name('categories.data');
    Route::resource('categories', CategoryController::class);
});

/*
|--------------------------------------------------------------------------
| Superadmin role
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'superadminDashboard'])->name('dashboard');

    // managing users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/assign-role', [UserController::class, 'assignRole'])->name('users.assignRole');
});
