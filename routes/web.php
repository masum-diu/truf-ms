<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TurfController as AdminTurfController;
use App\Http\Controllers\Admin\VendorController as AdminVendorController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TurfController;
use App\Http\Controllers\Vendor\BookingController as VendorBookingController;
use App\Http\Controllers\Vendor\DashboardController;
use App\Http\Controllers\Vendor\ProfileController;
use App\Http\Controllers\Vendor\TurfController as VendorTurfController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/turfs/{turf}', [TurfController::class, 'show'])->name('turfs.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::post('/turfs/{turf}/book', [TurfController::class, 'book'])->name('turfs.book');
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/my-bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('vendors', AdminVendorController::class);
    Route::get('/turfs', [AdminTurfController::class, 'index'])->name('turfs.index');
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
});

Route::prefix('vendor')->name('vendor.')->middleware(['auth', 'vendor'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/turf', [VendorTurfController::class, 'manage'])->name('turf.manage');
    Route::post('/turf', [VendorTurfController::class, 'store'])->name('turf.store');
    Route::put('/turf', [VendorTurfController::class, 'update'])->name('turf.update');
    Route::get('/bookings', [VendorBookingController::class, 'index'])->name('bookings.index');
    Route::patch('/bookings/{booking}/status', [VendorBookingController::class, 'updateStatus'])->name('bookings.status');
    Route::delete('/bookings/{booking}', [VendorBookingController::class, 'destroy'])->name('bookings.destroy');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
