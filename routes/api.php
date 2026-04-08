<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BranchController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\ImportController;
use App\Http\Controllers\API\RequestController;
use App\Http\Controllers\API\PIC\DashboardController as PICDashboardController;

// PUBLIC ROUTE
Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);

Route::get('/test', function(){
    return 'API OK';
});

  // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/activity', [DashboardController::class, 'activity']);

// 🔐 PROTECTED ROUTE
Route::middleware('auth:sanctum')->group(function () {

  
    // COMPANY
    Route::put('/company', [CompanyController::class, 'update']);

    // BRANCH
    Route::get('/branches', [BranchController::class, 'index']);
    Route::post('/branches', [BranchController::class, 'store']);
    Route::put('/branches/{id}/toggle', [BranchController::class, 'toggle']);
    Route::get('/branches-active', [BranchController::class, 'active']);
    Route::put('/branches/{id}', [BranchController::class, 'update']);
    Route::delete('/branches/{id}', [BranchController::class, 'destroy']);
    
    // USER
   Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::put('/users/{id}/activate', [UserController::class, 'activate']);
    Route::put('/users/{id}/toggle', [UserController::class, 'toggle']);
    Route::put('/users/{id}/reset-password', [UserController::class, 'resetPassword']);

    // IMPORT
    Route::post('/import-branches', [ImportController::class, 'importBranches']);
// PIC
    Route::get('/requests', [RequestController::class, 'index']);
    Route::post('/requests', [RequestController::class, 'store']);
    Route::get('/requests/{id}', [RequestController::class, 'show']);

    Route::middleware('auth:sanctum')->get('/pic/dashboard', [DashboardController::class, 'index']);
});

