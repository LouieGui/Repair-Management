<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProgressController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/repair-status', [StatusController::class, 'index'])->name('status');
Route::get('/new-request', [RequestController::class, 'index'])->name('request');
Route::get('/report', [ReportController::class, 'index'])->name('report');
Route::get('/tech-progress', [ProgressController::class, 'index'])->name('techProgress');

//Technician Progress Routes 
Route::post('/tech-progress/add', [ProgressController::class, 'addTech'])->name('progressAdd');
Route::delete('/tech-progress/{tech_id}/delete', [ProgressController::class, 'deleteTech'])->name('progressDelete');
