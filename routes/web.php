<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ShopController::class, 'index'])->name('products.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
//login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products/{custom_id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/contact', [ContactController::class, 'showForm'])->name('contact.form');
Route::post('/contact', action: [ContactController::class, 'store'])->name('contact.store');
       Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
Route::post('/wishlist/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');

// Cart routes (guests and users)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');

// Checkout routes (auth only)
Route::middleware(['auth'])->group(function () {
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/update', [ProfileController::class, 'update'])->name('profile.update');
    });
    Route::get('/orders/{order}/confirmation', [OrderController::class, 'orderConfirmation'])
        ->name('orders.confirmation');
    Route::get('/checkout/shipping', [CheckoutController::class, 'showShipping'])->name('checkout.shipping');
    Route::post('/checkout/shipping', [CheckoutController::class, 'storeShipping'])->name('checkout.shipping.store');
    Route::get('/checkout/review', [CheckoutController::class, 'showReview'])->name('checkout.review');
    Route::post('/checkout/place', [CheckoutController::class, 'placeOrder'])->name('checkout.place');
    Route::post('/products/{product}/rate', [ReviewController::class, 'store'])->name('products.rate');
    Route::put('/reviews/{review}',[ReviewController::class,'update'])->name('reviews.update');
    Route::delete('/reviews/{review}',[ReviewController::class,'destroy'])->name('reviews.destroy');
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/history', [OrderController::class, 'userOrders'])->name('orders.history');
    Route::get('/orders/{order}', [OrderController::class, 'userShow'])->name('orders.userShow');
    Route::resource('products', ProductController::class)->only(['index', 'create', 'store', 'edit', 'update']);
    // Addresses
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
});

Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login']);
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

Route::get('/admin/notifications/{notification}/redirect', function ($notificationId) {
    $notification = DatabaseNotification::findOrFail($notificationId);

    if ($notification['notifiable_id'] !== auth('admin')->id()) {
        abort(403, 'Unauthorized');
    }
    $notification->markAsRead();
    if (isset($notification->data['order_id'])) {
        return redirect()->route('admin.orders.show', $notification['data']['order_id']);
    }return redirect()->route('admin.dashboard');
})->prefix('admin.')->middleware(['auth:admin', 'role:admin|superadmin'])->name('notifications.redirect');

// Admin/superadmin Routes
Route::prefix('admin')->middleware(['admin.session', 'auth:admin', 'role:admin|superadmin'])->name('admin.')->group(function () {
                                                                                                                              // Contact management for admin/superadmin
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');                                     // list all contacts
    Route::get('/contacts/{id}', [ContactController::class, 'show'])->name('contacts.show');                                  // view contact details
    Route::patch('/contacts/{id}/update-status', [ContactController::class, 'updateStatus'])->name('contacts.updateStatus'); // update status
    Route::delete('/contacts/{id}', [ContactController::class, 'destroy'])->name('contacts.destroy');                         // delete contact

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard'); // admin.dashboard
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::resource('products', ProductController::class)->except(['destroy']);
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('orders', OrderController::class)->only(['index']);
    Route::get('/products/show/{custom_id}', [ProductController::class, 'show'])->name('products.show');
    Route::resource('customers', AdminCustomerController::class)->only(['index']);
    Route::resource('users', UserController::class)->except(['show', 'create']);
    Route::patch('/admin/users/{user}/update-roles', [UserController::class, 'updateRoles'])
        ->name('users.update-roles');
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
});

// Superadmin Routes
Route::prefix('admin')->middleware(['admin.session', 'auth:admin', 'role:superadmin'])->name('superadmin.')->group(function () {
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    // Manage suppliers
    Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('create');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('store');

    // Manage all users
    // User Management
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::patch('/users/{user}/roles', [UserController::class, 'updateRoles'])->name('users.update-roles');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});
