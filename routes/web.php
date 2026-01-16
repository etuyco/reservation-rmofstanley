<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;

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

Route::get('/', [PropertyController::class, 'index'])->name('home');

// Authentication Routes
Auth::routes();

// Property Routes (Public)
// Note: The homepage (/) uses the same controller method as /properties
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');

// Public Reservation Routes (Allow guests to create reservations)
Route::get('/properties/{property}/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
Route::post('/properties/{property}/reservations', [ReservationController::class, 'store'])->name('reservations.store');
Route::get('/api/properties/{property}/availability', [ReservationController::class, 'checkAvailability'])->name('api.properties.availability');
Route::get('/api/properties/{property}/calendar', [ReservationController::class, 'getCalendarAvailability'])->name('api.properties.calendar');

// Booking Routes (Authenticated)
Route::middleware(['auth'])->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/properties/{property}/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/properties/{property}/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
});

// Reservation Routes (Authenticated - for viewing and managing existing reservations)
Route::middleware(['auth'])->group(function () {
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/bookings', [AdminController::class, 'allBookings'])->name('bookings');
    Route::get('/reservations', [AdminController::class, 'allReservations'])->name('reservations');
    Route::post('/bookings/{booking}/approve', [AdminController::class, 'approveBooking'])->name('bookings.approve');
    Route::post('/bookings/{booking}/reject', [AdminController::class, 'rejectBooking'])->name('bookings.reject');
    Route::patch('/reservations/{reservation}/approve', [AdminController::class, 'approveReservation'])->name('reservations.approve');
    Route::patch('/reservations/{reservation}/reject', [AdminController::class, 'rejectReservation'])->name('reservations.reject');
    Route::patch('/reservations/{reservation}', [AdminController::class, 'updateReservation'])->name('reservations.update');
    Route::delete('/reservations/{reservation}', [AdminController::class, 'destroyReservation'])->name('reservations.destroy');
    
    // Property Management Routes
    Route::get('/properties', [PropertyController::class, 'adminIndex'])->name('properties.index');
    Route::get('/properties/create', [PropertyController::class, 'create'])->name('properties.create');
    Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
    Route::get('/properties/{property}/edit', [PropertyController::class, 'edit'])->name('properties.edit');
    Route::put('/properties/{property}', [PropertyController::class, 'update'])->name('properties.update');
    Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->name('properties.destroy');
});
