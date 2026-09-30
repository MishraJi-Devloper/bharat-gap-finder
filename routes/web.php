<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

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