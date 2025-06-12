<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AccountController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/repair-status', [StatusController::class, 'index'])->name('status');
Route::get('/new-request', [RequestController::class, 'index'])->name('request');
Route::get('/report', [ReportController::class, 'index'])->name('report');
Route::get('/account-customer', [AccountController::class, 'customer'])->name('customer');
Route::get('/account-technician', [AccountController::class, 'technician'])->name('technician');