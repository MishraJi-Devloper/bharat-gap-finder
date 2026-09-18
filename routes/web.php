<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/opportunity/{id}', [DashboardController::class, 'show'])->name('opportunity.show');
Route::get('/opportunity/{id}/pdf', [DashboardController::class, 'exportPdf'])->name('opportunity.pdf');