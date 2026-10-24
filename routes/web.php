<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;
use App\Http\Controllers\Provider\DashboardController as ProviderDashboard;
use App\Http\Controllers\Provider\ProfileController as ProviderProfile;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\BookingController as AdminBooking;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Auth;





Route::get('/', [LandingController::class, 'index'])->name('home');

Auth::routes();


// REDIRECT AFTER LOGIN
Route::get('/dashboard', function () {
    return match(Auth::user()->role) {
        'admin'    => redirect()->route('admin.dashboard'),
        'provider' => redirect()->route('provider.dashboard'),
        default    => redirect()->route('customer.dashboard'),
    };
})->middleware('auth')->name('dashboard');


// CUSTOMER ROUTES
Route::prefix('dashboard/customer')
    ->middleware(['auth', 'role:customer'])
    ->name('customer.')
    ->group(function () {

    Route::get('/', [CustomerDashboard::class, 'index'])->name('dashboard');
    Route::get('/bookings', [CustomerDashboard::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{booking}', [CustomerDashboard::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [CustomerDashboard::class, 'cancel'])->name('bookings.cancel');
    Route::post('/bookings/{booking}/review', [CustomerDashboard::class, 'review'])->name('bookings.review');
});


// BOOKING ROUTES
Route::prefix('booking')
    ->middleware(['auth', 'role:customer'])
    ->name('booking.')
    ->group(function () {

    Route::get('/', [BookingController::class, 'selectCategory'])->name('select-category');
    Route::get('/category/{category}', [BookingController::class, 'selectService'])->name('select-service');
    Route::get('/service/{service}', [BookingController::class, 'selectProvider'])->name('select-provider');
    Route::get('/service/{service}/provider/{provider}', [BookingController::class, 'form'])->name('form');
    Route::post('/store', [BookingController::class, 'store'])->name('store');
    Route::get('/success/{booking}', [BookingController::class, 'success'])->name('success');
});


// PROVIDER ROUTES
Route::prefix('dashboard/provider')
    ->middleware(['auth', 'role:provider'])
    ->name('provider.')
    ->group(function () {

    Route::get('/', [ProviderDashboard::class, 'index'])->name('dashboard');
    Route::get('/bookings', [ProviderDashboard::class, 'bookings'])->name('bookings');
    Route::post('/bookings/{booking}/confirm', [ProviderDashboard::class, 'confirm'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/reject', [ProviderDashboard::class, 'reject'])->name('bookings.reject');
    Route::post('/bookings/{booking}/done', [ProviderDashboard::class, 'done'])->name('bookings.done');

    Route::get('/profile', [ProviderProfile::class, 'edit'])->name('profile');
    Route::post('/profile', [ProviderProfile::class, 'update'])->name('profile.update');
    Route::post('/profile/services', [ProviderProfile::class, 'updateServices'])->name('profile.services');
});


// USER PROFILE ROUTES
Route::prefix('user')
    ->middleware(['auth'])
    ->name('user.')
    ->group(function () {
    Route::get('/profile', [\App\Http\Controllers\User\ProfileController::class, 'edit'])->name('profile');
    Route::post('/profile', [\App\Http\Controllers\User\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [\App\Http\Controllers\User\ProfileController::class, 'updatePassword'])->name('profile.password');
});


// ADMIN ROUTES
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {

    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/map', [AdminDashboard::class, 'map'])->name('map');

    Route::get('/bookings', [AdminBooking::class, 'index'])->name('bookings.index');
    Route::get('/bookings/export', [AdminBooking::class, 'export'])->name('bookings.export');
    Route::get('/bookings/{booking}', [AdminBooking::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/status', [AdminBooking::class, 'updateStatus'])->name('bookings.status');

    Route::resource('categories', CategoryController::class);

    Route::resource('services', ServiceController::class);

    Route::resource('users', UserController::class)->except(['create', 'store', 'edit', 'update']);
    Route::post('/users/{user}/verify-provider', [UserController::class, 'verifyProvider'])->name('users.verify-provider');
});




