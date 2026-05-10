<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ScheduleApiController;
use App\Http\Controllers\Api\ChildApiController;
use App\Http\Controllers\Api\MotherApiController;
use App\Http\Controllers\Api\KmsApiController;
use App\Http\Controllers\Api\KaderApiController;
use App\Http\Controllers\Api\ArticleApiController;
use App\Http\Controllers\Api\ReportApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All API routes are prefixed with /api
| Use auth:sanctum middleware for authentication
|
*/

// ========== AUTH ROUTES (Public) ==========
Route::post('/auth/login', [ApiAuthController::class, 'login']);
Route::post('/auth/register', [ApiAuthController::class, 'register']);

// ========== PROTECTED ROUTES ==========
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::get('/auth/me', [ApiAuthController::class, 'me']);
    Route::post('/auth/logout', [ApiAuthController::class, 'logout']);
    
    // ========== SCHEDULE API ==========
    // GET /api/schedules?posyandu_id=X - Ambil semua jadwal (admin & user)
    // GET /api/schedules/{id} - Detail jadwal
    // POST /api/schedules - Tambah jadwal (admin only)
    // PUT /api/schedules/{id} - Update jadwal (admin only)
    // DELETE /api/schedules/{id} - Hapus jadwal (admin only)
    Route::apiResource('schedules', ScheduleApiController::class);
    
    // ========== CHILDREN API ==========
    // GET /api/children?posyandu_id=X - Ambil semua anak (admin & user)
    // GET /api/children/{id} - Detail anak dengan records
    // POST /api/children - Tambah anak (admin & user)
    // PUT /api/children/{id} - Update anak (admin & user)
    // DELETE /api/children/{id} - Hapus anak (admin only)
    Route::apiResource('children', ChildApiController::class);
    
    // ========== IMMUNIZATION & VITAMIN API ==========
    // GET /api/children/{childId}/immunizations
    Route::get('/children/{child}/immunizations', [ChildApiController::class, 'immunizations']);
    Route::post('/children/{child}/immunizations', [ChildApiController::class, 'storeImmunization']);
    Route::put('/immunizations/{id}', [ChildApiController::class, 'updateImmunization']);
    Route::delete('/immunizations/{id}', [ChildApiController::class, 'destroyImmunization']);

    // GET /api/children/{childId}/vitamins
    Route::get('/children/{child}/vitamins', [ChildApiController::class, 'vitamins']);
    Route::post('/children/{child}/vitamins', [ChildApiController::class, 'storeVitamin']);
    
    // Static immunization schedule (WHO)
    Route::get('/immunization-schedule', [ChildApiController::class, 'immunizationSchedule']);
    
    // ========== MOTHERS API ==========
    // GET /api/mothers?posyandu_id=X - Ambil semua ibu hamil
    // GET /api/mothers/{id} - Detail ibu
    // POST /api/mothers - Tambah ibu hamil
    // PUT /api/mothers/{id} - Update ibu
    // DELETE /api/mothers/{id} - Hapus ibu (admin only)
    Route::apiResource('mothers', MotherApiController::class);
    
    // Pregnancy records
    Route::get('/mothers/{mother}/pregnancy', [MotherApiController::class, 'pregnancy']);
    Route::post('/mothers/{mother}/pregnancy', [MotherApiController::class, 'storePregnancy']);
    
    // Iron supplements
    Route::get('/mothers/{mother}/iron-supplements', [MotherApiController::class, 'ironSupplements']);
    Route::post('/mothers/{mother}/iron-supplements', [MotherApiController::class, 'storeIronSupplement']);
    
    // ========== KMS API ==========
    // GET /api/kms/child/{childId} - Growth records dengan Z-score
    Route::get('/kms/child/{child}', [KmsApiController::class, 'childGrowth']);
    Route::post('/kms/child/{child}/record', [KmsApiController::class, 'storeChildRecord']);
    
    // GET /api/kms/mother/{motherId} - Pregnancy records
    Route::get('/kms/mother/{mother}', [KmsApiController::class, 'motherPregnancy']);
    
    // Analytics (admin only)
    Route::get('/analytics/posyandu/{posyanduId}', [KmsApiController::class, 'posyanduAnalytics']);
    
    // ========== CADRES/KADER API ==========
    // GET /api/cadres?posyandu_id=X - Ambil semua kader
    // POST /api/cadres - Tambah kader (admin only)
    // PUT /api/cadres/{id} - Update kader (admin only)
    // PATCH /api/cadres/{id}/status - Update status kehadiran (admin only)
    // DELETE /api/cadres/{id} - Hapus kader (admin only)
    Route::apiResource('cadres', KaderApiController::class)->except(['index']);
    Route::get('/cadres', [KaderApiController::class, 'index']);
    Route::patch('/cadres/{cadre}/status', [KaderApiController::class, 'updateStatus']);
    
    // ========== ARTICLES API ==========
    // GET /api/articles?category=X - Ambil semua artikel
    // GET /api/articles/{id} - Detail artikel
    // POST /api/articles - Tambah artikel (admin only)
    // PUT /api/articles/{id} - Update artikel (admin only)
    // DELETE /api/articles/{id} - Hapus artikel (admin only)
    Route::apiResource('articles', ArticleApiController::class);
    
    // ========== REPORTS API ==========
    // GET /api/reports?posyandu_id=X - Ambil semua laporan
    // GET /api/reports/{id} - Detail laporan
    // POST /api/reports - Tambah laporan (admin only)
    // PUT /api/reports/{id} - Update laporan (admin only)
    // DELETE /api/reports/{id} - Hapus laporan (admin only)
    Route::apiResource('reports', ReportApiController::class);
    
    // ========== POSYANDU API ==========
    // GET /api/posyandus - Ambil semua posyandu
    Route::get('/posyandus', function() {
        $posyandus = \App\Models\Posyandu::all();
        return response()->json([
            'success' => true,
            'data' => $posyandus
        ]);
    });
});
