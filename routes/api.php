<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\DeviceController;
use App\Http\Controllers\Api\V1\RepairController;

// API Version 1 Routes
Route::prefix('v1')->group(function () {
    // User resource routes
    Route::apiResource('users', UserController::class)->except(['destroy']);
    Route::delete('users/{user}', [UserController::class, 'destroy']);

    // Customer resource routes
    Route::apiResource('customers', CustomerController::class)->except(['destroy']);
    Route::delete('customers/{customer}', [CustomerController::class, 'destroy']);

    // Device resource routes
    Route::apiResource('devices', DeviceController::class)->except(['destroy']);
    Route::delete('devices/{device}', [DeviceController::class, 'destroy']);

    // Repair resource routes
    Route::apiResource('repairs', RepairController::class)->except(['destroy']);
    Route::delete('repairs/{repair}', [RepairController::class, 'destroy']);

    // Additional repair routes
    Route::put('repairs/{repair}/status', [RepairController::class, 'updateStatus']);
    Route::get('customers/{customer}/repairs', [RepairController::class, 'findByCustomer']);
    Route::get('devices/{device}/repairs', [RepairController::class, 'findByDevice']);
    Route::get('technicians/{technician}/repairs', [RepairController::class, 'findByTechnician']);
    Route::get('repairs/status/{status}', [RepairController::class, 'findByStatus']);
});
