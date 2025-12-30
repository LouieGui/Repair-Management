<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\DeviceController;
use App\Http\Controllers\Api\V1\RepairController;
use App\Http\Controllers\Api\V1\ReturnController;
use App\Http\Controllers\Api\V1\PartController;

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

    // Return resource routes
    Route::apiResource('returns', ReturnController::class)->except(['destroy']);
    Route::delete('returns/{return}', [ReturnController::class, 'destroy']);

    // Additional return routes
    Route::put('returns/{return}/active', [ReturnController::class, 'toggleActive']);
    Route::get('repairs/{repair}/returns', [ReturnController::class, 'getByRepair']);
    Route::get('returns/type/{returnType}', [ReturnController::class, 'getByType']);
    Route::get('returns/warranty/{isUnderWarranty}', [ReturnController::class, 'getByWarrantyStatus']);
    Route::post('returns/warranty-claim', [ReturnController::class, 'processWarrantyClaim']);

    // Part resource routes
    Route::apiResource('parts', PartController::class)->except(['destroy']);
    Route::delete('parts/{part}', [PartController::class, 'destroy']);

    // Additional part routes
    Route::put('parts/{part}/active', [PartController::class, 'toggleActive']);
    Route::get('parts/search/{searchTerm}', [PartController::class, 'searchByName']);
    Route::get('parts/sku/{sku}', [PartController::class, 'findBySku']);
    Route::get('parts/low-stock/{threshold?}', [PartController::class, 'getLowStockParts']);
    Route::put('parts/{part}/add-stock/{quantity}', [PartController::class, 'addStock']);
    Route::put('parts/{part}/subtract-stock/{quantity}', [PartController::class, 'subtractStock']);
    Route::post('parts/bulk-import', [PartController::class, 'bulkImport']);
});
