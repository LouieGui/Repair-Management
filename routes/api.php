<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\DeviceController;
use App\Http\Controllers\Api\V1\RepairController;
use App\Http\Controllers\Api\V1\ReturnController;
use App\Http\Controllers\Api\V1\PartController;
use App\Http\Controllers\Api\V1\RepairPartController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\AuditTrailController;

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

    // Repair Part resource routes
    Route::apiResource('repair-parts', RepairPartController::class)->except(['destroy']);
    Route::delete('repair-parts/{repairPart}', [RepairPartController::class, 'destroy']);

    // Additional repair part routes
    Route::put('repair-parts/{repairPart}/active', [RepairPartController::class, 'toggleActive']);
    Route::get('repairs/{repair}/parts', [RepairPartController::class, 'getByRepair']);
    Route::get('parts/{part}/repairs', [RepairPartController::class, 'getByPart']);
    Route::get('repair-parts/approved', [RepairPartController::class, 'getApproved']);
    Route::get('repair-parts/pending', [RepairPartController::class, 'getPending']);
    Route::put('repair-parts/approve', [RepairPartController::class, 'approve']);
    Route::put('repair-parts/reject', [RepairPartController::class, 'reject']);
    Route::post('repair-parts/calculate-total', [RepairPartController::class, 'calculateTotal']);
    Route::post('repairs/{repair}/quotation', [RepairPartController::class, 'createQuotation']);
    Route::post('repairs/{repair}/approve-quotation', [RepairPartController::class, 'approveQuotation']);

    // Payment resource routes
    Route::apiResource('payments', PaymentController::class)->except(['destroy']);
    Route::delete('payments/{payment}', [PaymentController::class, 'destroy']);

    // Additional payment routes
    Route::put('payments/{payment}/active', [PaymentController::class, 'toggleActive']);
    Route::get('repairs/{repair}/payments', [PaymentController::class, 'getByRepair']);
    Route::get('payments/status/{status}', [PaymentController::class, 'getByStatus']);
    Route::get('payments/method/{paymentMethod}', [PaymentController::class, 'getByMethod']);
    Route::get('payments/successful', [PaymentController::class, 'getSuccessful']);
    Route::get('payments/failed', [PaymentController::class, 'getFailed']);
    Route::get('payments/pending', [PaymentController::class, 'getPending']);
    Route::post('payments/process', [PaymentController::class, 'processPayment']);
    Route::post('payments/{payment}/refund', [PaymentController::class, 'refundPayment']);
    Route::get('payments/statistics', [PaymentController::class, 'getStatistics']);
    Route::get('payments/revenue/{status?}', [PaymentController::class, 'calculateRevenue']);

    // Audit Trail resource routes
    Route::apiResource('audit-trails', AuditTrailController::class)->except(['show', 'update', 'destroy']);
    Route::post('audit-trails', [AuditTrailController::class, 'store']);

    // Additional audit trail routes
    Route::get('users/{user}/audit-trails', [AuditTrailController::class, 'getByUser']);
    Route::get('audit-trails/user-type/{userType}', [AuditTrailController::class, 'getByUserType']);
    Route::get('audit-trails/table/{tableName}', [AuditTrailController::class, 'getByTable']);
    Route::get('audit-trails/event/{event}', [AuditTrailController::class, 'getByEvent']);
    Route::get('audit-trails/date-range/{startDate}/{endDate}', [AuditTrailController::class, 'getByDateRange']);
    Route::get('audit-trails/recent/{limit?}', [AuditTrailController::class, 'getRecent']);
    Route::get('audit-trails/statistics', [AuditTrailController::class, 'getStatistics']);
    Route::delete('audit-trails/clear/{cutoffDate}', [AuditTrailController::class, 'clearOldAuditTrails']);
});
