<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\ChatController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return view('home');
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword']);
Route::post('/forgot-password', [AuthController::class, 'processForgotPassword']);
Route::get('/reset-password', [AuthController::class, 'showResetPassword']);
Route::post('/reset-password', [AuthController::class, 'processResetPassword']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('index');
    Route::get('/informasi', [App\Http\Controllers\DashboardController::class, 'informasi'])->name('informasi');
    Route::get('/informasi/anak', [App\Http\Controllers\DashboardController::class, 'informasiAnak'])->name('informasi.anak');
    Route::get('/informasi/ibu', [App\Http\Controllers\DashboardController::class, 'informasiIbu'])->name('informasi.ibu');
    Route::get('/kms', [App\Http\Controllers\DashboardController::class, 'kms'])->name('kms');
    Route::get('/kader', [App\Http\Controllers\DashboardController::class, 'kader'])->name('kader');
    Route::get('/artikel', [App\Http\Controllers\DashboardController::class, 'artikel'])->name('artikel');
    
// API routes for updating status
    Route::post('/update-imunisasi', [App\Http\Controllers\DashboardController::class, 'updateImunisasi'])->name('update.imunisasi');
    Route::post('/update-vitamin', [App\Http\Controllers\DashboardController::class, 'updateVitamin'])->name('update.vitamin');
    Route::post('/update-tt', [App\Http\Controllers\DashboardController::class, 'updateTT'])->name('update.tt');
    Route::post('/update-trimester', [App\Http\Controllers\DashboardController::class, 'updateTrimester'])->name('update.trimester');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/jadwal', [App\Http\Controllers\AdminController::class, 'jadwal'])->name('jadwal');
    Route::post('/jadwal', [App\Http\Controllers\AdminController::class, 'jadwalStore'])->name('jadwal.store');
    Route::put('/jadwal/{schedule}', [App\Http\Controllers\AdminController::class, 'jadwalUpdate'])->name('jadwal.update');
    Route::delete('/jadwal/{schedule}', [App\Http\Controllers\AdminController::class, 'jadwalDestroy'])->name('jadwal.destroy');
    Route::get('/informasi', [App\Http\Controllers\AdminController::class, 'informasi'])->name('informasi');
Route::get('/informasi/anak', [App\Http\Controllers\AdminController::class, 'informasi'])->name('informasi.anak');
    Route::get('/informasi/ibu', [App\Http\Controllers\AdminController::class, 'informasi'])->name('informasi.ibu');
    Route::get('/informasi/edit/anak/{child}', [App\Http\Controllers\AdminController::class, 'informasiEditAnak'])->name('informasi.edit.anak');
    Route::get('/informasi/edit/ibu/{mother}', [App\Http\Controllers\AdminController::class, 'informasiEditIbu'])->name('informasi.edit.ibu');
Route::post('/informasi/update-status', [App\Http\Controllers\AdminController::class, 'updateStatusAdmin'])->name('informasi.update.status');
    Route::post('/informasi/update-imunisasi', [App\Http\Controllers\AdminController::class, 'updateImunisasiAdmin'])->name('informasi.update.imunisasi');
    Route::post('/informasi/update-vitamin', [App\Http\Controllers\AdminController::class, 'updateVitaminAdmin'])->name('informasi.update.vitamin');
    Route::post('/informasi/update-tt', [App\Http\Controllers\AdminController::class, 'updateTTAdmin'])->name('informasi.update.tt');
    Route::post('/informasi/update-trimester', [App\Http\Controllers\AdminController::class, 'updateTrimesterAdmin'])->name('informasi.update.trimester');
    Route::post('/informasi/update-status-child', [App\Http\Controllers\AdminController::class, 'updateStatusChild'])->name('informasi.update.status.child');
    Route::post('/informasi/update-status-mother', [App\Http\Controllers\AdminController::class, 'updateStatusMother'])->name('informasi.update.status.mother');
    Route::get('/kms', [App\Http\Controllers\AdminController::class, 'kmsAnalytics'])->name('kms');
    Route::get('/kader', [App\Http\Controllers\AdminController::class, 'kader'])->name('kader');
    Route::post('/kader', [App\Http\Controllers\AdminController::class, 'kaderStore'])->name('kader.store');
    Route::put('/kader/{kader}', [App\Http\Controllers\AdminController::class, 'kaderUpdate'])->name('kader.update');
    Route::patch('/kader/{kader}/status', [App\Http\Controllers\AdminController::class, 'kaderUpdateStatus'])->name('kader.updateStatus');
    Route::delete('/kader/{kader}', [App\Http\Controllers\AdminController::class, 'kaderDestroy'])->name('kader.destroy');
    
// Article Management Routes
    Route::get('/artikel', [App\Http\Controllers\AdminController::class, 'artikel'])->name('artikel');
    Route::post('/artikel', [App\Http\Controllers\AdminController::class, 'artikelStore'])->name('artikel.store');
    Route::put('/artikel/{article}', [App\Http\Controllers\AdminController::class, 'artikelUpdate'])->name('artikel.update');
    Route::delete('/artikel/{article}', [App\Http\Controllers\AdminController::class, 'artikelDestroy'])->name('artikel.destroy');
    
// KMS Ibu - Data kesehatan ibu hamil (BB, LILA, Tekanan Darah)
    Route::get('/kms-ibu', [App\Http\Controllers\AdminController::class, 'kmsIbu'])->name('kms-ibu');
    Route::get('/kms-ibu/get-data', [App\Http\Controllers\AdminController::class, 'kmsIbuGetData'])->name('kms-ibu.get-data');
    Route::post('/kms-ibu/store', [App\Http\Controllers\AdminController::class, 'kmsIbuStore'])->name('kms-ibu.store');
    Route::get('/kms-ibu/riwayat', [App\Http\Controllers\AdminController::class, 'kmsIbuGetRiwayat'])->name('kms-ibu.riwayat');
    
    // KMS Anak - Data kesehatan anak (BB, TB, Status Gizi, Stunting)
    Route::get('/kms-anak', [App\Http\Controllers\AdminController::class, 'kmsAnak'])->name('kms-anak');
    Route::get('/kms-anak/get-data', [App\Http\Controllers\AdminController::class, 'kmsAnakGetData'])->name('kms-anak.get-data');
    Route::post('/kms-anak/store', [App\Http\Controllers\AdminController::class, 'kmsAnakStore'])->name('kms-anak.store');
    Route::put('/kms-anak/{record}', [App\Http\Controllers\AdminController::class, 'kmsAnakUpdate'])->name('kms-anak.update');
    Route::delete('/kms-anak/{record}', [App\Http\Controllers\AdminController::class, 'kmsAnakDestroy'])->name('kms-anak.destroy');
    Route::get('/kms-anak/riwayat', [App\Http\Controllers\AdminController::class, 'kmsAnakGetRiwayat'])->name('kms-anak.riwayat');
    Route::get('/kms-anak/grafik', [App\Http\Controllers\AdminController::class, 'kmsAnakGrafik'])->name('kms-anak.grafik');
    
// Laporan Posyandu
    Route::get('/laporan', [App\Http\Controllers\AdminController::class, 'laporan'])->name('laporan');
    Route::get('/laporan/get-detail', [App\Http\Controllers\AdminController::class, 'laporanGetDetail'])->name('laporan.get-detail');
    Route::post('/laporan/export', [App\Http\Controllers\AdminController::class, 'laporanExport'])->name('laporan.export');
    Route::get('/laporan/anak', [App\Http\Controllers\AdminController::class, 'laporanAnak'])->name('laporan.anak');
    Route::get('/laporan/ibu-hamil', [App\Http\Controllers\AdminController::class, 'laporanIbuHamil'])->name('laporan.ibu-hamil');
    Route::get('/laporan/imunisasi', [App\Http\Controllers\AdminController::class, 'laporanImunisasi'])->name('laporan.imunisasi');
    Route::get('/laporan/grafik', [App\Http\Controllers\AdminController::class, 'laporanGrafik'])->name('laporan.grafik');
    Route::get('/laporan/gizi', [App\Http\Controllers\AdminController::class, 'laporanGizi'])->name('laporan.gizi');
    Route::get('/laporan/stunting', [App\Http\Controllers\AdminController::class, 'laporanStunting'])->name('laporan.stunting');
    Route::get('/laporan/arsip', [App\Http\Controllers\AdminController::class, 'laporanArsip'])->name('laporan.arsip');
    Route::post('/laporan/generate', [App\Http\Controllers\AdminController::class, 'laporanGenerate'])->name('laporan.generate');
    Route::post('/laporan/arsip/simpan', [App\Http\Controllers\AdminController::class, 'laporanArsipSimpan'])->name('laporan.arsip.simpan');
    Route::get('/laporan/export-pdf', [App\Http\Controllers\AdminController::class, 'laporanExportPdf'])->name('laporan.export-pdf');
    Route::get('/laporan/export-excel', [App\Http\Controllers\AdminController::class, 'laporanExportExcel'])->name('laporan.export-excel');
});

Route::middleware('auth')->group(function () {
    Route::resource('children', ChildController::class);
    
// Mothers CRUD
    Route::get('/mothers', [App\Http\Controllers\MotherController::class, 'listIndex'])->name('mothers.index');
    Route::get('/mothers/create', [App\Http\Controllers\MotherController::class, 'create'])->name('mothers.create');
    Route::post('/mothers', [App\Http\Controllers\MotherController::class, 'store'])->name('mothers.store');
    Route::get('/mothers/{mother}/edit', [App\Http\Controllers\MotherController::class, 'edit'])->name('mothers.edit');
    Route::put('/mothers/{mother}', [App\Http\Controllers\MotherController::class, 'update'])->name('mothers.update');
    Route::delete('/mothers/{mother}', [App\Http\Controllers\MotherController::class, 'destroy'])->name('mothers.destroy');
});

Route::get('/whatsapp-generator', function () {
    return view('whatsapp.generator');
});

Route::post('/chat', [ChatController::class, 'chat']);
// Duplicate children resource removed - auth middleware group sudah cover
