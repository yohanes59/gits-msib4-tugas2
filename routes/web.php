<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::redirect('/register', '/login');
});

Route::middleware(['admin-only', 'auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::controller(CategoryController::class)->prefix('/kategori')->group(function () {
        // parameter -> route, method, asname
        Route::get('', 'index')->name('kategori');
        Route::get('tambah', 'create')->name('kategori.tambah');
        Route::post('tambah', 'store')->name('kategori.tambah.simpan');
        Route::get('edit/{id}', 'edit')->name('kategori.edit');
        Route::post('edit/{id}', 'update')->name('kategori.edit.update');
        Route::get('hapus/{id}', 'destroy')->name('kategori.hapus');
    });

    Route::controller(ProductController::class)->prefix('/produk')->group(function () {
        // parameter -> route, method, asname
        Route::get('', 'index')->name('produk');
        Route::get('tambah', 'create')->name('produk.tambah');
        Route::post('tambah', 'store')->name('produk.tambah.simpan');
        Route::get('edit/{id}', 'edit')->name('produk.edit');
        Route::post('edit/{id}', 'update')->name('produk.edit.update');
        Route::get('hapus/{id}', 'destroy')->name('produk.hapus');
    });
});

Route::middleware('auth')->group(function () {
    Route::controller(CartController::class)->prefix('/')->group(function () {
        // parameter -> route, method, asname
        Route::middleware('user-only')->group(function () {
            Route::get('', 'index')->name('home');
            Route::post('', 'store')->name('cart.tambah.simpan');
            Route::put('update/{id}', 'update')->name('cart.update');
            Route::get('hapus/{id}', 'destroy')->name('cart.hapus');
        });
    });

    Route::get('/logout', [AuthController::class, 'logout']);
});
