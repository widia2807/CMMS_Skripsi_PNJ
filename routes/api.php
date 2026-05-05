<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\{
    AuthController,
    BranchController,
    CompanyController,
    UserController,
    DashboardController,
    ImportController,
    RequestController,
    LiteDashboardController,
    TechnicianDashboardController,
    AssetController,
    AssetCategoryController,
    AssetSubCategoryController
};
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Http\Controllers\PIC\DashboardController as PICDashboardController;

//Public
Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);

Route::get('/test', fn() => 'API OK');


Route::middleware('auth:sanctum')->group(function () {

    
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/activity', [DashboardController::class, 'activity']);
    Route::get('/lite/dashboard', [LiteDashboardController::class, 'index']);
    Route::get('/pic/dashboard', [PICDashboardController::class, 'index']);

   
    Route::put('/company', [CompanyController::class, 'update']);

    Route::get('/branches', [BranchController::class, 'index']);
    Route::post('/branches', [BranchController::class, 'store']);
    Route::put('/branches/{id}', [BranchController::class, 'update']);
    Route::delete('/branches/{id}', [BranchController::class, 'destroy']);
    Route::put('/branches/{id}/toggle', [BranchController::class, 'toggle']);
    Route::get('/branches-active', [BranchController::class, 'active']);

    
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::put('/users/{id}/activate', [UserController::class, 'activate']);
    Route::put('/users/{id}/toggle', [UserController::class, 'toggle']);
    Route::put('/users/{id}/reset-password', [UserController::class, 'resetPassword']);

  
    Route::post('/import-branches', [ImportController::class, 'importBranches']);

    
    Route::get('/requests', [RequestController::class, 'index']);
    Route::post('/requests', [RequestController::class, 'store']);
    Route::get('/requests/{id}', [RequestController::class, 'show']);
    Route::get('/categories', function () {
    return \App\Models\Category::select('id', 'name')->get();
    });
    Route::get('/sub-categories', function () {
        return \App\Models\SubCategory::with('category')->get();
    });
    Route::post('/categories', function (Request $request) {
    return Category::create([
        'name' => $request->name
    ]);
    });

    Route::post('/sub-categories', function (Request $request) {
        return SubCategory::create([
            'name' => $request->name,
            'category_id' => $request->category_id
        ]);
    });
    Route::get('/request/sub-categories/{category}', [RequestController::class, 'getSubCategory']);

    Route::post('/requests/{id}/approve', [RequestController::class, 'approve']);
    Route::put('/requests/{id}/reject', [RequestController::class, 'reject']);
    Route::post('/requests/{id}/assign-technician', [RequestController::class, 'assignTechnician']);

    
    Route::get('/materials', [RequestController::class, 'materialRequests']);
    Route::post('/materials/{id}/approve', [RequestController::class, 'approveMaterial']);
    Route::post('/materials/approve-all/{id}', [RequestController::class, 'approveAllMaterial']);

   
    Route::apiResource('assets', AssetController::class);
    Route::get('/assets/export', [AssetController::class, 'export']);

    Route::get('/asset/categories', [AssetCategoryController::class, 'index']);
    Route::post('/asset/categories', [AssetCategoryController::class, 'store']);

    Route::get('/asset/sub-categories', [AssetSubCategoryController::class, 'index']);
    Route::post('/asset/sub-categories', [AssetSubCategoryController::class, 'store']);

    
    Route::get('/dashboard-technician', [TechnicianDashboardController::class, 'index']);
    Route::get('/technicians', [TechnicianDashboardController::class, 'technicians']);
    Route::get('/technician/jobs', [TechnicianDashboardController::class, 'jobs']);

    Route::post('/assign-technician/{id}', [TechnicianDashboardController::class, 'assignTechnician']);
    Route::post('/technician/schedule/{id}', [TechnicianDashboardController::class, 'schedule']);
    Route::post('/technician/start/{id}', [TechnicianDashboardController::class, 'startJob']);
    Route::post('/technician/complete/{id}', [TechnicianDashboardController::class, 'completeJob']);
    Route::post('/technician/inspect/{id}', [TechnicianDashboardController::class, 'inspectJob']);
    Route::post('/technician/material/{id}', [TechnicianDashboardController::class, 'requestMaterial']);

        // Daftar semua jadwal (support ?status=, ?worker_id=, ?month=)
    Route::get('/scheduled-maintenances', [ScheduledMaintenanceController::class, 'index']);
 
    // Detail satu jadwal
    Route::get('/scheduled-maintenances/{id}', [ScheduledMaintenanceController::class, 'show']);
 
    // Buat jadwal baru
    Route::post('/scheduled-maintenances', [ScheduledMaintenanceController::class, 'store']);
 
    // Ubah tukang (reassign)
    Route::put('/scheduled-maintenances/{id}/assign', [ScheduledMaintenanceController::class, 'assign']);
 
    // Hapus jadwal (hanya status pending)
    Route::delete('/scheduled-maintenances/{id}', [ScheduledMaintenanceController::class, 'destroy']);
 
 
    /* ─── TUKANG ─── */
 
    // Tugas milik tukang yang sedang login (support ?month=)
    Route::get('/scheduled-maintenances/my-tasks', [ScheduledMaintenanceController::class, 'myTasks']);
 
    // Konfirmasi jadwal → status: pending → confirmed
    Route::put('/scheduled-maintenances/{id}/confirm', [ScheduledMaintenanceController::class, 'confirm']);
 
    // Tandai selesai + upload foto → status: confirmed/in_progress → done
    Route::post('/scheduled-maintenances/{id}/complete', [ScheduledMaintenanceController::class, 'complete']);
 
 
    /* ─── SHARED ─── */
 
    // Daftar tukang (untuk dropdown di form Admin GA)
    // Sesuaikan filter role dengan sistem role yang kamu pakai
    Route::get('/workers', function () {
        return \App\Models\User::where('role', 'tukang')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
    });


});