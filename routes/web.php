<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShippingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');


// Dashboard redirect (WAJIB ADA AGAR LOGIN TIDAK ERROR)
Route::get('/dashboard', function () {
    if (Auth::check()) {

         // Check super admin first
        if (auth::user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Redirect untuk admin
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Redirect untuk customer
        return redirect()->route('home');
    }

    return redirect()->route('login');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');


    // Orders (Customer)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});


// Admin Routes
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // User Management (Super Admin Only)
    Route::middleware('super_admin')->group(function () {
        Route::resource('users', App\Http\Controllers\Admin\UserManagementController::class);
        Route::post('/users/{user}/toggle-status', [App\Http\Controllers\Admin\UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
    });


        Route::get('/dashboard', 
            [App\Http\Controllers\Admin\DashboardController::class, 'index']
        )->name('dashboard');

        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
        Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
        Route::resource('orders', App\Http\Controllers\Admin\OrderController::class)
            ->only(['index', 'show', 'update']);

            // Shipping Routes
        Route::get('/orders/{order}/shipping/create', [App\Http\Controllers\Admin\ShippingController::class, 'create'])->name('shipping.create');
        Route::post('/orders/{order}/shipping', [App\Http\Controllers\Admin\ShippingController::class, 'store'])->name('shipping.store');
        Route::put('/shipping/{shipping}', [App\Http\Controllers\Admin\ShippingController::class, 'update'])->name('shipping.update');
        

        // Reports Routes
        Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');
        });

        // Customer Routes - Payment Confirmation
        Route::middleware('auth')->group(function () {
         // ... existing routes ...

    // Payment Confirmation
        Route::get('/orders/{order}/payment/create', [App\Http\Controllers\PaymentConfirmationController::class, 'create'])->name('payment.create');
        Route::post('/orders/{order}/payment', [App\Http\Controllers\PaymentConfirmationController::class, 'store'])->name('payment.store');
        Route::get('/orders/{order}/payment', [App\Http\Controllers\PaymentConfirmationController::class, 'show'])->name('payment.show');
    });

    // Admin Routes - Payment Confirmation
        Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        // ... existing routes ...

    // Payment Confirmations
        Route::get('/payments', [App\Http\Controllers\Admin\PaymentConfirmationController::class, 'index'])->name('payments.index');
        Route::get('/payments/{paymentConfirmation}', [App\Http\Controllers\Admin\PaymentConfirmationController::class, 'show'])->name('payments.show');
        Route::post('/payments/{paymentConfirmation}/approve', [App\Http\Controllers\Admin\PaymentConfirmationController::class, 'approve'])->name('payments.approve');
        Route::post('/payments/{paymentConfirmation}/reject', [App\Http\Controllers\Admin\PaymentConfirmationController::class, 'reject'])->name('payments.reject');
});
require __DIR__.'/auth.php';
