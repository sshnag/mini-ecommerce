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
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Supplier\SupplierDashboardController;
use App\Http\Controllers\Supplier\SupplierOrderController;
use App\Http\Controllers\Supplier\SupplierProductController;

use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Admin\SupplierController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
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


Route::get('/products/{custom_id}', [ProductController::class, 'show'])->name('products.show');

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
    Route::resource('products', ProductController::class)->only(['index', 'create', 'store', 'edit', 'update']);

    // Addresses
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');



});

Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login']);
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

// Admin/superadmin Routes
Route::prefix('admin')->middleware(['auth:admin', 'role:admin|superadmin'])->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
            Route::get('customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('users', [UserController::class, 'index'])->name('users.index');
Route::resource('products', ProductController::class)->except(['destroy']);
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
         Route::get('users/create',[UserController::class,'create'])->name('users.create');
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
     Route::get('users/edit',[UserController::class,'edit'])->name('users.edit');
     Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('orders', OrderController::class)->only(['index']);

    Route::resource('customers', AdminCustomerController::class)->only(['index']);
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('suppliers', SupplierController::class)->except(['show']);

});


// Supplier Routes
Route::middleware(['auth', 'role:supplier'])->prefix('supplier')->name('supplier.')->group(function () {
    Route::get('/dashboard', [SupplierDashboardController::class, 'index'])->name('supplier.dashboard');
    Route::resource('products', SupplierProductController::class);
    Route::get('orders', [SupplierOrderController::class, 'index'])->name('orders.index');
});

// Superadmin Routes
Route::prefix('admin')->middleware(['auth:admin', 'role:superadmin'])->name('superadmin.')->group(function () {
   Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Manage all users
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
});
