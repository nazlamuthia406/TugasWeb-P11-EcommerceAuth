<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Tugas Rutin 11 (E-Commerce DB + Secure Auth)
|--------------------------------------------------------------------------
| Lapisan keamanan:
|   (publik)         : beranda, katalog, detail produk
|   auth             : harus login (keranjang, pesanan, kelola produk, profil)
|   auth + role:admin: panel admin
|   Policy           : edit/hapus produk dicek lagi di controller (Gate::authorize)
*/

Route::get('/', HomeController::class)->name('home');

/* ===== Area login ===== */
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // PENTING: didaftarkan SEBELUM resource index/show di bawah,
    // supaya "/products/create" tidak ditangkap oleh "/products/{product}".
    Route::resource('products', ProductController::class)->except(['index', 'show']);
    Route::get('/manage/products', [ProductController::class, 'manage'])->name('products.manage');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{product}', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* ===== Katalog publik ===== */
Route::resource('products', ProductController::class)->only(['index', 'show']);

/* ===== Panel admin: login + role admin ===== */
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}', [Admin\UserController::class, 'update'])->name('users.update');

    Route::get('/orders', [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{order}', [Admin\OrderController::class, 'update'])->name('orders.update');

    Route::get('/eager-loading', Admin\EagerLoadingController::class)->name('eager');
});

require __DIR__ . '/auth.php';
