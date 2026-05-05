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
    Route::get('/informasi/anak', [App\Http\Controllers\AdminController::class, 'informasi'])->name('informasi.anak');
    Route::get('/informasi/ibu', [App\Http\Controllers\AdminController::class, 'informasi'])->name('informasi.ibu');
    Route::get('/kms', [App\Http\Controllers\AdminController::class, 'kmsAnalytics'])->name('kms');
    Route::get('/kader', [App\Http\Controllers\AdminController::class, 'kader'])->name('kader');
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
