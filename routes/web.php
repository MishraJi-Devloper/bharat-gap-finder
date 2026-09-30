<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminBusinessController;
use App\Http\Controllers\AdminInvitationController;

Route::middleware('auth')->group(function () {
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/opportunity/{id}', [DashboardController::class, 'show'])->name('opportunity.show');
Route::get('/opportunity/{id}/pdf', [DashboardController::class, 'exportPdf'])->name('opportunity.pdf');

// Demand Ingestion
Route::get('/demand/create', [DashboardController::class, 'createDemand'])->name('demand.create');
Route::post('/demand/store', [DashboardController::class, 'storeDemand'])->name('demand.store');

// Supply Ingestion
Route::get('/supply/create', [DashboardController::class, 'createSupply'])->name('supply.create');
Route::post('/supply/store', [DashboardController::class, 'storeSupply'])->name('supply.store');

// MSME Registry
Route::get('/businesses', [DashboardController::class, 'businesses'])->name('businesses');
Route::get('/businesses/create', [DashboardController::class, 'createBusiness'])->name('businesses.create');
Route::post('/businesses', [DashboardController::class, 'storeBusiness'])->name('businesses.store');

Route::get('/businesses/{business}', [DashboardController::class, 'showBusiness'])->name('businesses.show');

// District Comparison
Route::get('/compare', [DashboardController::class, 'compare'])->name('compare');
});

// Authentication
Route::middleware('guest')->group(function () {
	Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
	Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.store');
	Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
	Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
	Route::get('/businesses', [AdminBusinessController::class, 'index'])->name('businesses');
	Route::patch('/businesses/{business}/status', [AdminBusinessController::class, 'updateStatus'])->name('businesses.status');
	Route::get('/invitations', [AdminInvitationController::class, 'index'])->name('invitations');
	Route::post('/invitations', [AdminInvitationController::class, 'store'])->name('invitations.store');
});