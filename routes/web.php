<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Dashboard & Analytics
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/opportunity/{id}', [DashboardController::class, 'show'])->name('opportunity.show');
Route::get('/opportunity/{id}/pdf', [DashboardController::class, 'exportPdf'])->name('opportunity.pdf');

// Dynamic Data Ingestion Pipeline
Route::get('/demand/create', [DashboardController::class, 'createDemand'])->name('demand.create');
Route::post('/demand/store', [DashboardController::class, 'storeDemand'])->name('demand.store');