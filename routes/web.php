<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Api\NearbyHotelsController;
use App\Http\Controllers\ApiStatusController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SyncController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/hotels', [HotelController::class, 'index']);
Route::get('/flights', [FlightController::class, 'index']);
Route::get('/packages', [PackageController::class, 'index']);
Route::get('/things-to-do', [ActivityController::class, 'index']);
Route::get('/destination/{slug}', [DestinationController::class, 'show']);

Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1');
Route::get('/signup', [AuthController::class, 'showSignup']);
Route::post('/signup', [AuthController::class, 'signup'])->middleware('throttle:10,1');
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/my-bookings', [BookingController::class, 'index']);
Route::post('/book', [BookingController::class, 'store'])->middleware('throttle:20,1');
Route::post('/cancel-booking', [BookingController::class, 'cancel'])->middleware('throttle:20,1');

Route::get('/about', [PageController::class, 'about']);
Route::get('/api-status', [ApiStatusController::class, 'index']);

// Maintenance actions are restricted to admins (see EnsureUserIsAdmin).
Route::middleware('admin')->group(function () {
    Route::post('/sync-destinations', [SyncController::class, 'destinations']);
    Route::post('/sync-hotels', [SyncController::class, 'hotels']);
    Route::post('/clear-cache', [SyncController::class, 'clearCache']);
});

Route::get('/set-language/{lang}', [LanguageController::class, 'set']);
Route::post('/newsletter', [NewsletterController::class, 'store'])->middleware('throttle:10,1');

Route::get('/api/nearby-hotels', [NearbyHotelsController::class, 'index'])->middleware('throttle:30,1');

// Simple informational pages (so footer links don't all point to About).
Route::get('/terms', [PageController::class, 'terms']);
Route::get('/privacy', [PageController::class, 'privacy']);
Route::get('/contact', [PageController::class, 'contact']);
