<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CheckoutController;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ShopController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Supplier\ProductController as SupplierProductController;
use App\Models\Supplier;


Route::get('/products', [ShopController::class, 'index'])->name('products.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
//login
Route::get('/login',[LoginController::class,'showLoginForm'])->name('login');
Route::post('/login',[LoginController::class,'login']);
Route::post('/logout',[LoginController::class,'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('/products/{custom_id}', [ProductController::class, 'show'])
     ->name('products.show');
//  User Routes
    Route::middleware(['auth', 'role:user|admin|supplier|superadmin'])->group(function () {
Route::get('/orders/{order}/confirmation', [OrderController::class, 'orderConfirmation'])
    ->name('orders.confirmation');
    Route::get('/checkout/shipping', [CheckoutController::class,'showShipping'])->name('checkout.shipping');
    Route::post('/checkout/shipping', [CheckoutController::class,'storeShipping'])->name('checkout.shipping.store');
    Route::get('/checkout/review', [CheckoutController::class,'showReview'])->name('checkout.review');
    Route::post('/checkout/place', [CheckoutController::class,'placeOrder'])->name('checkout.place');
   Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Addresses
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');

    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login']);
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

// Admin/superadmin Routes
Route::prefix('admin')->middleware(['auth:admin', 'role:admin|superadmin'])->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('products',[ProductController::class,'index'])->name('products');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
         Route::get('users/create',[UserController::class,'create'])->name('users.create');
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
     Route::get('users/edit',[UserController::class,'edit'])->name('users.edit');

});


// Supplier Routes
Route::prefix('supplier')->middleware(['auth', 'role:supplier'])->name('supplier.')->group(function () {

    // Manage own products
    Route::get('products', [SupplierProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [SupplierProductController::class, 'create'])->name('products.create');
    Route::post('products', [SupplierProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}/edit', [SupplierProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [SupplierProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [SupplierProductController::class, 'destroy'])->name('products.destroy');

});

// Superadmin Routes
Route::prefix('superadmin')->middleware(['auth', 'role:superadmin'])->name('superadmin.')    ->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Manage all users
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
});
